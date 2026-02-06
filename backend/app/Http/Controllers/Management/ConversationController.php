<?php

namespace App\Http\Controllers\Management;

use App\Contracts\ConversationServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConversationRequest;
use App\Http\Resources\ConversationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function __construct(
        private ConversationServiceInterface $conversationService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $conversations = $request->user()
            ->conversations()
            ->with(['participants', 'latestMessage.sender'])
            ->orderByDesc('last_message_at')
            ->get();

        return response()->json(ConversationResource::collection($conversations)->resolve());
    }

    public function store(StoreConversationRequest $request): JsonResponse
    {
        $conversation = $this->conversationService->createConversation($request->validated(), $request->user());

        return (new ConversationResource($conversation))->response()->setStatusCode(201);
    }
}
