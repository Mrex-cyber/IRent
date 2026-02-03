<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Services\ConversationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function __construct(
        private ConversationService $conversationService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $conversations = $request->user()
            ->conversations()
            ->with(['participants', 'latestMessage.sender'])
            ->orderByDesc('last_message_at')
            ->get();

        $data = $conversations->map(fn (Conversation $c) => $this->conversationService->formatConversationForChat($c));

        return response()->json($data->values()->all());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'nullable|in:direct,group',
            'participant_ids' => 'nullable|array',
            'participant_ids.*' => 'integer|exists:users,id',
        ]);

        $type = $validated['type'] ?? 'direct';
        if ($type === 'group') {
            $participantIds = array_values(array_unique(array_merge([$request->user()->id], $validated['participant_ids'] ?? [])));
            if (count($participantIds) < 2) {
                return response()->json(
                    ['message' => 'Group must have at least one other participant.'],
                    422
                );
            }
        }

        $conversation = $this->conversationService->createConversation($validated, $request->user());

        return response()->json($this->conversationService->formatConversationForChat($conversation), 201);
    }
}
