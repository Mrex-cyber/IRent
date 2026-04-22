<?php

namespace App\Services;

use App\Contracts\ConversationServiceInterface;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;

class ConversationService implements ConversationServiceInterface
{
    public function getInboxForUser(
        User $user,
        ?string $category = null,
        ?string $searchFieldText = null,
        string $tab = 'messages',
    ): array {
        $query = Conversation::query()
            ->whereHas('participants', fn ($q) => $q->where('users.id', $user->id))
            ->with([
                'latestMessage.sender',
                'participants' => fn ($q) => $q->with([
                    'ownedApartments.entrance.building',
                    'rentedApartments.entrance.building',
                ]),
            ])
            ->withCount('participants');

        if ($tab === 'groups') {
            $query->where('type', 'group')
                ->has('participants', '>=', 3);
        } else {
            $query->where('type', '!=', 'group');
        }

        $categoryEnum = $this->categoryEnumFromFilterLabel($category);
        if ($categoryEnum !== null) {
            $query->where('category', $categoryEnum);
        }

        $term = $searchFieldText !== null ? trim($searchFieldText) : '';
        $like = null;
        if ($term !== '') {
            $like = '%'.addcslashes($term, '%_\\').'%';
            $categoryEnumsForTag = $this->categoryEnumsMatchingSearchTerm($term);
            $query->where(function ($outer) use ($like, $categoryEnumsForTag) {
                $outer->where('conversations.subject', 'like', $like)
                    ->orWhereHas('latestMessage', function ($mq) use ($like) {
                        $mq->where('body', 'like', $like);
                    })
                    ->orWhereHas('latestMessage.sender', function ($q) use ($like) {
                        $q->where(function ($nameQ) use ($like) {
                            $nameQ->where('users.first_name', 'like', $like)
                                ->orWhere('users.last_name', 'like', $like)
                                ->orWhereRaw("trim(concat_ws(' ', users.first_name, users.last_name)) like ?", [$like]);
                        });
                    })
                    ->orWhere(function ($groupName) use ($like) {
                        $groupName->where('conversations.type', 'group')
                            ->whereHas('participants', function ($pq) use ($like) {
                                $pq->where(function ($nameQ) use ($like) {
                                    $nameQ->where('users.first_name', 'like', $like)
                                        ->orWhere('users.last_name', 'like', $like)
                                        ->orWhereRaw("trim(concat_ws(' ', users.first_name, users.last_name)) like ?", [$like]);
                                });
                            });
                    });
                if ($categoryEnumsForTag !== []) {
                    $outer->orWhereIn('conversations.category', $categoryEnumsForTag);
                }
            });
        }

        $conversations = $query->orderByDesc('last_message_at')->get();

        return $conversations->map(fn (Conversation $c) => $this->formatInboxItem($c, $user))->values()->all();
    }

    private function categoryEnumFromFilterLabel(?string $category): ?string
    {
        if ($category === null || $category === '' || strcasecmp($category, 'All') === 0) {
            return null;
        }

        return match ($category) {
            'Suggestions and complaints' => 'suggestions_complaints',
            'Legal Matter' => 'legal',
            'Maintenance' => 'maintenance',
            'Financial' => 'financial',
            'General' => 'general',
            default => null,
        };
    }

    private function categoryEnumsMatchingSearchTerm(string $term): array
    {
        $term = trim($term);
        if ($term === '') {
            return [];
        }

        $map = [
            'suggestions_complaints' => 'Suggestions and complaints',
            'legal' => 'Legal Matter',
            'maintenance' => 'Maintenance',
            'financial' => 'Financial',
            'general' => 'General',
        ];

        $matches = [];
        foreach ($map as $enum => $label) {
            if (mb_stripos($label, $term) !== false || mb_stripos($enum, $term) !== false) {
                $matches[] = $enum;
            }
        }

        return $matches;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getConversationMessages(Conversation $conversation, User $user): Collection
    {
        if (! $this->userCanAccessConversation($conversation, $user)) {
            return new Collection;
        }

        return $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();
    }

    public function getConversationMessagesByMessageId(int $messageId, User $user): array
    {
        $message = Message::findOrFail($messageId);
        $conversation = $message->conversation;

        if (! $this->userCanAccessConversation($conversation, $user)) {
            return [];
        }

        return $conversation->messages()
            ->orderBy('created_at')
            ->get()
            ->map(fn (Message $m) => [
                'id' => $m->id,
                'content' => $m->body,
                'timestamp' => $m->created_at->toIso8601String(),
                'sent' => (int) $m->user_id === (int) $user->id,
            ])
            ->values()
            ->all();
    }

    public function createReply(int $messageId, User $user, string $content): array
    {
        $message = Message::findOrFail($messageId);
        $conversation = $message->conversation;

        if (! $this->userCanAccessConversation($conversation, $user)) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Unauthorized');
        }

        $newMessage = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => $content,
        ]);

        $conversation->update(['last_message_at' => $newMessage->created_at]);

        return [
            'id' => $newMessage->id,
            'content' => $newMessage->body,
            'timestamp' => $newMessage->created_at->toIso8601String(),
            'sent' => true,
        ];
    }

    public function addMessageToConversation(Conversation $conversation, User $user, string $content): Message
    {
        if (! $this->userCanAccessConversation($conversation, $user)) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Unauthorized');
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => $content,
        ]);

        $conversation->update(['last_message_at' => $message->created_at]);

        return $message;
    }

    public function userCanAccessConversation(Conversation $conversation, User $user): bool
    {
        $participantIds = $conversation->participants()->pluck('user_id')->all();

        return in_array($user->id, $participantIds);
    }

    public function createConversation(array $validated, User $user): Conversation
    {
        $type = $validated['type'] ?? 'direct';
        $participantIds = $validated['participant_ids'] ?? [];

        if ($type === 'group') {
            $participantIds = array_values(array_unique(array_merge([$user->id], $participantIds)));
        }

        $conversation = Conversation::create([
            'type' => $type,
            'category' => 'general',
            'subject' => $validated['name'],
            'last_message_at' => now(),
        ]);

        if ($type === 'group') {
            $conversation->participants()->attach($participantIds);
        } else {
            $conversation->participants()->attach($user->id);
        }

        return $conversation->load(['participants', 'latestMessage.sender']);
    }

    /**
     * @return array{conversation: Conversation, message: Message}
     */
    public function createBroadcast(array $validated, User $user): array
    {
        $recipientIds = array_values(array_unique(array_merge([$user->id], $validated['recipient_ids'])));

        $conversation = Conversation::create([
            'type' => 'broadcast',
            'category' => 'general',
            'subject' => $validated['subject'],
            'last_message_at' => now(),
        ]);

        $conversation->participants()->attach($recipientIds);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => $validated['content'],
        ]);

        $conversation->update(['last_message_at' => $message->created_at]);

        return ['conversation' => $conversation, 'message' => $message];
    }

    public function categoryLabel(string $category): string
    {
        return match ($category) {
            'suggestions_complaints' => 'Suggestions and complaints',
            'legal' => 'Legal Matter',
            'maintenance' => 'Maintenance',
            'financial' => 'Financial',
            default => 'General',
        };
    }

    private function formatInboxItem(Conversation $c, User $viewer): array
    {
        $last = $c->latestMessage;
        $sender = $last?->sender;
        $participantNames = $c->participants
            ->map(fn ($u) => trim($u->first_name.' '.$u->last_name))
            ->values()
            ->all();

        $building = null;
        $apartment = null;
        if (in_array($c->type, ['direct', 'broadcast'], true)) {
            foreach ($c->participants as $participant) {
                if ((int) $participant->id === (int) $viewer->id) {
                    continue;
                }
                $apt = $participant->ownedApartments->first() ?? $participant->rentedApartments->first();
                if ($apt !== null) {
                    $building = $apt->entrance?->building?->address;
                    $apartment = $apt->number;
                    break;
                }
            }
        }

        return [
            'id' => $last?->id ?? $c->id,
            'conversation_id' => $c->id,
            'type' => $c->type,
            'participant_count' => $c->participants_count,
            'participant_names' => $participantNames,
            'subject' => $c->subject,
            'senderName' => $sender ? trim($sender->first_name.' '.$sender->last_name) : 'Unknown',
            'senderRole' => $sender ? ($sender->roles()->first()?->label ?? 'Member') : 'Member',
            'content' => $last?->body ?? '',
            'category' => $this->categoryLabel($c->category),
            'date' => $last?->created_at->format('Y-m-d') ?? $c->created_at->format('Y-m-d'),
            'building' => $building,
            'apartment' => $apartment,
        ];
    }
}
