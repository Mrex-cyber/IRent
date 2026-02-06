<?php

namespace App\Http\Controllers\Management;

use App\Contracts\ConversationServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyMessageRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageInboxController extends Controller
{
    public function __construct(
        private ConversationServiceInterface $conversationService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $inbox = $this->conversationService->getInboxForUser($request->user());

        return response()->json($inbox);
    }

    public function conversation(Request $request, int $message): JsonResponse
    {
        $messages = $this->conversationService->getConversationMessagesByMessageId($message, $request->user());

        if (empty($messages)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($messages);
    }

    public function reply(ReplyMessageRequest $request, int $message): JsonResponse
    {
        try {
            $data = $this->conversationService->createReply(
                $message,
                $request->user(),
                $request->validated()['content']
            );

            return response()->json($data, 201);
        } catch (AuthorizationException) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }
}
