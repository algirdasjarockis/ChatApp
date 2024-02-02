<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\ParticipantRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MessageController extends AbstractController
{
    private ConversationRepository $conversationRepository;
    private MessageRepository $messageRepository;
    private ParticipantRepository $participantRepository;
    private HubInterface $hub;

    public function __construct(
        ConversationRepository $conversationRepository, 
        MessageRepository $messageRepository,
        ParticipantRepository $participantRepository,
        HubInterface $hub
    ) {
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
        $this->participantRepository = $participantRepository;
        $this->hub = $hub;
    }

    #[Route('/messages/{id}', name: 'app_messages_list')]
    #[IsGranted('view', 'conversation')]
    public function index(Request $request, Conversation $conversation): Response
    {
        $messages = $this->messageRepository->findByConversation($conversation);

        array_map(
            fn($message) => $message->setIsMine($message->getAppUser()->getId() === $this->getUser()->getId()), 
            $messages ?? []);

        return $this->json(
            $messages, 
            Response::HTTP_OK, 
            []
        );
    }

    #[Route('/newMessage/{id}', name: 'app_messages_new', methods: ['POST'])]
    public function newMessage(Request $request, Conversation $conversation): Response
    {
        $content = trim($request->request->get('content', null));

        if (empty($content)) {
            throw new BadRequestException();
        }

        $message = new Message();
        $message->setAppUser($this->getUser());
        $message->setContent($content);
        $message->setIsMine(true);

        $conversation->addMessage($message);
        $conversation->setLastMessage($message);
        
        $this->messageRepository->saveMessage($message);
        $this->conversationRepository->saveConversation($conversation);

        $messageForMercure = $messageToResponse = $message->jsonSerialize();

        $messageForMercure['userId'] = $this->getUser()->getId();
        $update = new Update(
            ["conversations/{$conversation->getId()}"],
            json_encode($messageForMercure),
            false
        );

        $this->hub->publish($update);

        return $this->json($messageToResponse, Response::HTTP_CREATED, [], ['attributes' => ['id','content']]);
    }

    //private function publishToMercure()
}
