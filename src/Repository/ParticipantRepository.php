<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Participant>
 *
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    public function isUserConversationParticipant(User $user, Conversation $conversation): bool
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->select('p.id')
            ->andWhere('p.conversation = :conversation')
            ->andWhere('p.appUser = :user')
            ->setParameters([
                'user'=> $user,
                'conversation'=> $conversation
            ]);

        $result = $qb->getQuery()->getResult();

        return count($result) > 0;
    }

    public function findParticipantsByConversation(Conversation $conversation): array
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
            ->select()
            ->andWhere('p.conversation = :conversation')
            ->setParameter('conversation', $conversation)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Participant[] Returns an array of Participant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Participant
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
