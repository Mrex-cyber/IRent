<?php

namespace App\Http\Resources;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Message
 */
class MessageResource extends JsonResource
{
    public function __construct(
        $resource,
        private ?int $conversationId = null
    ) {
        parent::__construct($resource);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $sender = $this->relationLoaded('sender') ? $this->sender : $this->sender()->first();
        $roomId = $this->conversationId ?? $this->conversation_id;

        return [
            'id' => (string) $this->id,
            'content' => $this->body,
            'senderId' => (string) $this->user_id,
            'senderName' => $sender ? trim($sender->first_name.' '.$sender->last_name) : 'Unknown',
            'senderAvatar' => null,
            'roomId' => (string) $roomId,
            'timestamp' => $this->created_at->toIso8601String(),
            'type' => 'text',
        ];
    }
}
