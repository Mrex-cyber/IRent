<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\ConversationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageInboxController extends Controller
{
    public function __construct(
        private ConversationService $conversationService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $inbox = $this->conversationService->getInboxForUser($request->user());

        return response()->json($inbox);
    }

    public function conversation(Request $request, int $message): JsonResponse
    {
        $user = $request->user();
        $messages = $this->conversationService->getConversationMessagesByMessageId($message, $user);

        if (empty($messages)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($messages);
    }

    public function reply(Request $request, int $message): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|max:65535',
        ]);

        try {
            $data = $this->conversationService->createReply(
                $message,
                $request->user(),
                $validated['content']
            );

            return response()->json($data, 201);
        } catch (\Illuminate\Auth\Access\AuthorizationException) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }
}
