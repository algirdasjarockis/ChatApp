<?php

namespace App\Service\Chat;

use App\Entity\Conversation;
use App\Entity\User;

interface ConversationServiceInterface
{
    public function createConversation(User $currentUser, int $targetUserId): Conversation;

    public function fetchConversations(int $userId) : array;
}
