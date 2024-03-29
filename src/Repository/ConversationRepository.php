<?php

namespace App\Repository;

use App\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversation>
 *
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function findConversationByParticipants(int $myId, int $otherUser)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select($qb->expr()->count('p.conversation'))
            ->innerJoin('c.participants', 'p')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('p.appUser', ':me'),
                    $qb->expr()->eq('p.appUser', ':otherUser')
                )
            )
            ->groupBy('p.conversation')
            ->having(
                $qb->expr()->gte(
                    $qb->expr()->count('p.conversation'), 
                    2
                )
            )
            ->setParameters([
                'me' => $myId,
                'otherUser' => $otherUser
            ])
        ;

        return $qb->getQuery()->getResult();
    }

    public function findConversationsByUser(int $userId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->select('otherUser.username', 'otherUser.displayName', 'otherUser.avatarFileName', 'c.id as conversationId', 'm.content', 'm.createdAt')
            ->innerJoin('c.participants', 'p', Join::WITH, $qb->expr()->neq('p.appUser', ':user'))
            ->innerJoin('c.participants', 'me', Join::WITH, $qb->expr()->neq('p.appUser', ':user'))
            ->leftJoin('c.lastMessage', 'm')
            ->innerJoin('me.appUser', 'myUser')
            ->innerJoin('p.appUser', 'otherUser')
            ->where('myUser.id = :user')
            ->setParameter('user', $userId)
            ->orderBy('m.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
