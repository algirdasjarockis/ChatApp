<?php
namespace App\Service\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService implements UserServiceInterface
{
    private UserPasswordHasherInterface $userPasswordHasher;

    private EntityManagerInterface $entityManager;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->entityManager = $entityManager;
    }

    public function persistNewUser(User $user, string $plainPassword, bool $flush = true): void
    {
        $user->setPassword(
            $this->userPasswordHasher->hashPassword($user, $plainPassword)
        );

        $this->entityManager->persist($user);
        $flush && $this->entityManager->flush();
    }
}