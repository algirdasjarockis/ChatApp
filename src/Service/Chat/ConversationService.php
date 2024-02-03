<?php

namespace App\Service\Chat;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class ConversationService implements ConversationServiceInterface
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

    public function createConversation(User $currentUser, int $targetUserId): Conversation
    {
        $targetUser = $this->userRepository->find($targetUserId);

        if (is_null($targetUser)) {
            throw new UserNotFoundException("User with provided id $targetUserId was not found");
        }

        if ($targetUserId == $currentUser->getId()) {
            throw new \LogicException("Provided id can not be same as current user's id");
        }

        $result = $this->conversationRepository->findConversationByParticipants(
            $targetUser->getId(), $currentUser->getId());

        if ($result !== null && count($result) > 0) {
            throw new \LogicException("Conversation already exists");
        }

        $newConversation = $this->createAndPersistConversation($currentUser, $targetUser);

        return $newConversation;
    }

    public function fetchConversations(int $userId) : array
    {
        return $this->conversationRepository->findConversationsByUser($userId);
    }

    private function createAndPersistConversation(User $currentUser, User $targetUser) : Conversation
    {
        $conversation = new Conversation();

        $this->entityManager->beginTransaction();

        try {
            foreach ([$targetUser, $currentUser] as $user) {
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
