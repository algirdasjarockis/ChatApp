<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\WebLink\Link;

class ConversationController extends AbstractController
{
    private UserRepository $userRepository;

    private ConversationRepository $conversationRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager,
        ConversationRepository $conversationRepository
    ) {
        $this->userRepository = $userRepository;
        $this->conversationRepository = $conversationRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/startConversation/{userId}', name: 'app_conversation_start')]
    public function startConversation(Request $request, int $userId): Response
    {
        $otherUser = $this->userRepository->find($userId);

        if (is_null($otherUser)) {
            throw new NotFoundHttpException("User with provided id $userId was not found");
        }

        if ($userId == $this->getUser()->getId()) {
            throw new \LogicException("Provided id can not be same as current user's id");
        }

        $result = $this->conversationRepository->findConversationByParticipants(
            $otherUser->getId(), $this->getUser()->getId());

        if ($result !== null && count($result) > 0) {
            throw new \LogicException("Conversation already exists");
        }

        $newConversation = $this->createConversation($otherUser);

        return $this->json([
            'id' => $newConversation->getId()
        ], Response::HTTP_CREATED);
    }

    #[Route('/conversations', name:'app_conversations_get')]
    public function getConversations(Request $request): Response
    {
        $conversations = $this->conversationRepository->findConversationsByUser($this->getUser()->getId());

        $this->addLink($request, new Link('mercure', $this->getParameter('mercure_hub_url')));

        return $this->json($conversations);
    }

    private function createConversation(User $withUser) : Conversation
    {
        $conversation = new Conversation();

        $this->entityManager->beginTransaction();

        try {
            foreach ([$withUser, $this->getUser()] as $user) {
                $participant = new Participant();
                $participant->setAppUser($user);
                $participant->setConversation($conversation);
                $conversation->addParticipant($participant);
    
                $this->entityManager->persist($participant);
            }

            $this->entityManager->persist($conversation);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
        }

        return $conversation;
    }
}
