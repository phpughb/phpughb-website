<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\User\TokenGenerator;
use App\Domain\User\UserCreateTokenManager;
use App\Domain\User\UserManager;
use App\Entity\UserCreateToken;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

/**
 * @internal
 * @coversNothing
 */
final class UserCreateTokenManagerTest extends TestCase
{
    /**
     * @test
     * @covers \App\Domain\User\UserCreateTokenManager::activate
     */
    public function userCreateTokenIsRemovedAfterActivation(): void
    {
        $userCreateToken = new UserCreateToken('some-token', 'some@email.de', false);
        $password = 'some-password';

        $tokenGenerator = $this->prophesize(TokenGenerator::class);
        $entityManager = $this->prophesize(EntityManagerInterface::class);
        $mailer = $this->prophesize(MailerInterface::class);
        $twig = $this->prophesize(Environment::class);
        $userManager = $this->prophesize(UserManager::class);

        $entityManager
            ->remove($userCreateToken)
            ->shouldBeCalled()
        ;
        $entityManager
            ->flush()
            ->shouldBeCalled()
        ;

        $userCreateTokenManager = new UserCreateTokenManager(
            $tokenGenerator->reveal(),
            $entityManager->reveal(),
            $mailer->reveal(),
            $twig->reveal(),
            $userManager->reveal(),
            'some-other@email.de'
        );
        $userCreateTokenManager->activate($userCreateToken, $password);
    }
}
