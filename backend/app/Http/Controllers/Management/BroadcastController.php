<?php

namespace App\Http\Controllers\Management;

use App\Contracts\ConversationServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBroadcastRequest;
use App\Http\Resources\BroadcastCreatedResource;
use Illuminate\Http\JsonResponse;

class BroadcastController extends Controller
{
    public function __construct(
        private ConversationServiceInterface $conversationService
    ) {}

    public function store(StoreBroadcastRequest $request): JsonResponse
    {
        $result = $this->conversationService->createBroadcast($request->validated(), $request->user());

        return (new BroadcastCreatedResource($result))->response()->setStatusCode(201);
    }
}
