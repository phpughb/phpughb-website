<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private UserPasswordEncoderInterface $userPasswordEncoder;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserPasswordEncoderInterface $userPasswordEncoder,
        EntityManagerInterface $entityManager
    ) {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entityManager;
    }

    public function create(string $email, string $plainPassword, bool $isAdmin = false): void
    {
        $role = $isAdmin ? 'ROLE_ADMIN' : 'ROLE_USER';

        $user = new User($email, $role);
        $encodedPassword = $this->userPasswordEncoder->encodePassword($user, $plainPassword);
        $user->setEncodedPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
