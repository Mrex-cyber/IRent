<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageCollection extends ResourceCollection
{
    public function __construct(
        $resource,
        private ?int $conversationId = null
    ) {
        parent::__construct($resource);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(
            fn ($message) => (new MessageResource($message, $this->conversationId))->toArray($request)
        )->values()->all();
    }
}
