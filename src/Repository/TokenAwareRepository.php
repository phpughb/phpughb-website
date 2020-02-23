<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserCreateToken;

interface TokenAwareRepository
{
    /**
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByToken(string $token): UserCreateToken;
}
