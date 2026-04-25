<?php

namespace App\Http\Resources;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BroadcastCreatedResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var array{conversation: Conversation, message: Message} $payload */
        $payload = $this->resource;

        return [
            'conversation_id' => $payload['conversation']->id,
            'message_id' => $payload['message']->id,
        ];
    }
}
