<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\ConversationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function __construct(
        private ConversationService $conversationService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string|max:65535',
            'recipient_ids' => 'required|array',
            'recipient_ids.*' => 'integer|exists:users,id',
        ]);

        $result = $this->conversationService->createBroadcast($validated, $request->user());

        return response()->json([
            'conversation_id' => $result['conversation']->id,
            'message_id' => $result['message']->id,
        ], 201);
    }
}
