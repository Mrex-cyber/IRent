<?php

namespace App\Http\Resources;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Conversation
 */
class ConversationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $latest = $this->relationLoaded('latestMessage')
            ? $this->latestMessage
            : $this->latestMessage()->with('sender')->first();
        $sender = $latest?->sender;

        return [
            'id' => (string) $this->id,
            'name' => $this->subject ?? 'Conversation #'.$this->id,
            'description' => null,
            'participants' => $this->participants->map(fn ($u) => [
                'id' => (string) $u->id,
                'name' => trim($u->first_name.' '.$u->last_name),
                'email' => $u->email,
            ])->values()->all(),
            'lastMessage' => $latest ? [
                'id' => (string) $latest->id,
                'content' => $latest->body,
                'senderId' => (string) $latest->user_id,
                'senderName' => $sender ? trim($sender->first_name.' '.$sender->last_name) : 'Unknown',
                'roomId' => (string) $this->id,
                'timestamp' => $latest->created_at->toIso8601String(),
                'type' => 'text',
            ] : null,
            'createdAt' => $this->created_at->toIso8601String(),
            'updatedAt' => $this->updated_at->toIso8601String(),
        ];
    }
}
