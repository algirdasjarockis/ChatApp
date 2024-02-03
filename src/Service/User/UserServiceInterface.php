<?php

namespace App\Service\User;
use App\Entity\User;

interface UserServiceInterface
{
    public function persistNewUser(User $user, string $plainPassword, bool $flush = true): void;
}