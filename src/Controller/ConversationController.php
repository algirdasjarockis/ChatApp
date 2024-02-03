<?php

namespace App\Controller;

use App\Service\Chat\ConversationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\WebLink\Link;

class ConversationController extends AbstractController
{
    private ConversationServiceInterface $conversationService;

    public function __construct(ConversationServiceInterface $conversationService) 
    {
        $this->conversationService = $conversationService;
    }

    #[Route('/startConversation/{targetUserId}', name: 'app_conversation_start')]
    public function startConversation(Request $request, int $targetUserId): Response
    {
        try {
            $conversation = $this->conversationService->createConversation($this->getUser(), $targetUserId);
        } catch (UserNotFoundException $e) {
            throw new NotFoundHttpException("User '$targetUserId' was not found", $e);
        } catch (\LogicException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return $this->json([
            'id' => $conversation->getId()
        ], Response::HTTP_CREATED);
    }

    #[Route('/conversations', name:'app_conversations_get')]
    public function getConversations(Request $request): Response
    {
        $conversations = $this->conversationService->fetchConversations($this->getUser()->getId());

        $this->addLink($request, new Link('mercure', $this->getParameter('mercure_hub_url')));

        return $this->json(
            [
                'hubUrl' => $this->getParameter('mercure_hub_url'),
                'topics' => array_map(fn($conversation) => "conversations/{$conversation['conversationId']}", $conversations),
                'conversations' => $conversations,
            ]
        );
    }
}
