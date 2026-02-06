<?php

namespace App\Services;

use App\Contracts\ConversationServiceInterface;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;

class ConversationService implements ConversationServiceInterface
{
    public function getInboxForUser(User $user): array
    {
        $conversations = $user->conversations()
            ->with(['latestMessage.sender', 'participants'])
            ->withCount('participants')
            ->orderByDesc('last_message_at')
            ->get();

        return $conversations->map(fn (Conversation $c) => $this->formatInboxItem($c))->values()->all();
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

    private function formatInboxItem(Conversation $c): array
    {
        $last = $c->latestMessage;
        $sender = $last?->sender;
        $participantNames = $c->participants
            ->map(fn ($u) => trim($u->first_name.' '.$u->last_name))
            ->values()
            ->all();

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
            'building' => null,
            'apartment' => null,
        ];
    }
}
