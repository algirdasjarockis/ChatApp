<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Service\Chat\MessageServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MessageController extends AbstractController
{
    private MessageServiceInterface $messageService;

    public function __construct(MessageServiceInterface $messageService) 
    {
        $this->messageService = $messageService;
    }

    #[Route('/messages/{id}', name: 'app_messages_list')]
    #[IsGranted('view', 'conversation')]
    public function index(Request $request, Conversation $conversation): Response
    {
        return $this->json(
            $this->messageService->fetchMessages($conversation, $this->getUser()->getId()),
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

        $message = $this->messageService->createMessage($conversation, $content, $this->getUser());

        return $this->json($message, Response::HTTP_CREATED);
    }
}
