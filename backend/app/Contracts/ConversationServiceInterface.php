<?php

namespace App\Contracts;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

interface ConversationServiceInterface
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function getInboxForUser(User $user): array;

    /**
     * @return \Illuminate\Support\Collection<int, \App\Models\Message>
     */
    public function getConversationMessages(Conversation $conversation, User $user): \Illuminate\Support\Collection;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getConversationMessagesByMessageId(int $messageId, User $user): array;

    /**
     * @return array<string, mixed>
     */
    public function createReply(int $messageId, User $user, string $content): array;

    public function addMessageToConversation(Conversation $conversation, User $user, string $content): Message;

    public function userCanAccessConversation(Conversation $conversation, User $user): bool;

    public function createConversation(array $validated, User $user): Conversation;

    /**
     * @return array{conversation: Conversation, message: Message}
     */
    public function createBroadcast(array $validated, User $user): array;
}
