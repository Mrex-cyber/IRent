<?php

namespace App\Http\Controllers\Management;

use App\Contracts\ConversationServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(
        private ConversationServiceInterface $conversationService
    ) {}

    public function index(Request $request, Conversation $conversation): JsonResponse
    {
        if (! $this->conversationService->userCanAccessConversation($conversation, $request->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = $this->conversationService->getConversationMessages($conversation, $request->user());

        return response()->json((new MessageCollection($messages, $conversation->id))->toArray($request));
    }

    public function store(StoreMessageRequest $request, Conversation $conversation): JsonResponse
    {
        try {
            $message = $this->conversationService->addMessageToConversation(
                $conversation,
                $request->user(),
                $request->validated()['content']
            );
            $message->load('sender');

            return (new MessageResource($message, $conversation->id))
                ->response()
                ->setStatusCode(201);
        } catch (AuthorizationException) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }
}
