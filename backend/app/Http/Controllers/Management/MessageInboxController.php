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
        $category = $request->query('category');
        $category = is_string($category) ? trim($category) : null;
        if ($category === '') {
            $category = null;
        }

        $searchFieldText = $request->query('searchFieldText');
        $searchFieldText = is_string($searchFieldText) ? mb_substr(trim($searchFieldText), 0, 255) : null;
        if ($searchFieldText === '') {
            $searchFieldText = null;
        }

        $tab = $request->query('tab', 'messages');
        $tab = is_string($tab) && in_array($tab, ['messages', 'groups'], true) ? $tab : 'messages';

        $inbox = $this->conversationService->getInboxForUser(
            $request->user(),
            $category,
            $searchFieldText,
            $tab,
        );

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
