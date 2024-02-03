<?php

namespace App\Service\Chat;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class MessageService implements MessageServiceInterface
{
    private MessageRepository $messageRepository;
    private EntityManagerInterface $em;
    private HubInterface $hub;

    public function __construct(
        MessageRepository $messageRepository,
        EntityManagerInterface $em,
        HubInterface $hub
    ) {
        $this->messageRepository = $messageRepository;
        $this->em = $em;
        $this->hub = $hub;
    }

    public function fetchMessages(Conversation $conversation, int $currentUserId): iterable
    {
        $messages = $this->messageRepository->findByConversation($conversation);

        array_map(
            fn($message) => $message->setIsMine($message->getAppUser()->getId() === $currentUserId), 
            $messages ?? []);

        return $messages;
    }

    public function createMessage(Conversation $conversation, string $content, User $user): Message
    {
        $message = new Message();
        $message->setAppUser($user);
        $message->setContent($content);
        $message->setIsMine(true);

        $conversation->addMessage($message);
        $conversation->setLastMessage($message);
        
        $this->persistMessage($message, $conversation);
        $this->publishMessage($message, $conversation, $user);

        return $message;
    }

    private function persistMessage(Message $message, Conversation $conversation): void
    {
        $this->em->beginTransaction();
            $this->em->persist($message);
            $this->em->persist($conversation);
            $this->em->flush();
        $this->em->commit();
    }

    private function publishMessage(Message $message, Conversation $conversation, User $user): void
    {
        $messageForMercure = $message->jsonSerialize();
        $messageForMercure['userId'] = $user->getId();

        $update = new Update(
            ["conversations/{$conversation->getId()}"],
            json_encode($messageForMercure),
            false
        );

        $this->hub->publish($update);
    }
}
