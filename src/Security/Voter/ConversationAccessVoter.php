<?php

namespace App\Security\Voter;
use App\Entity\Conversation;
use App\Repository\ParticipantRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConversationAccessVoter extends Voter
{
    const VIEW = 'view';

    private ParticipantRepository $participantRepository;

    public function __construct(ParticipantRepository $participantRepository)
    {
        $this->participantRepository = $participantRepository;
    }

    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, [self::VIEW])) {
            return false;
        }

        if (!$subject instanceof Conversation) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return $this->participantRepository->isUserConversationParticipant($token->getUser(), $subject);
    }
}