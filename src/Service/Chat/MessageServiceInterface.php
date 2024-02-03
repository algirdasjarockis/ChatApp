<?php

namespace App\Service\Chat;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;

interface MessageServiceInterface
{
   /**
    * @return Message[] Returns an array of Message objects
    */
    public function fetchMessages(Conversation $conversation, int $currentUserId): iterable;

    public function createMessage(Conversation $conversation, string $content, User $user): Message;
}
