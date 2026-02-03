<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Services\ConversationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(
        private ConversationService $conversationService
    ) {}

    public function index(Request $request, Conversation $conversation): JsonResponse
    {
        if (! $this->conversationService->userCanAccessConversation($conversation, $request->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = $this->conversationService->getConversationMessages($conversation, $request->user());

        return response()->json($messages);
    }

    public function store(Request $request, Conversation $conversation): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|max:65535',
        ]);

        try {
            $message = $this->conversationService->addMessageToConversation(
                $conversation,
                $request->user(),
                $validated['content']
            );
            $message->load('sender');

            return response()->json(
                $this->conversationService->formatMessageForApi($message, $conversation->id),
                201
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }
}
