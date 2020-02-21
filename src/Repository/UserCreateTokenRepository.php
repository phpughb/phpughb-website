<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserCreateToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserCreateTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCreateToken::class);
    }

    public function findOneByToken(string $token): ?UserCreateToken
    {
        $queryBuilder = $this->createQueryBuilder('user_create_token');
        $queryBuilder
            ->where('user_create_token.token = :token')
            ->setParameter('token', $token);

        return $queryBuilder->getQuery()->getResult()[0] ?? null;
    }
}
