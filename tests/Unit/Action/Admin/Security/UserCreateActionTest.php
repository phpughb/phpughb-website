<?php

declare(strict_types=1);

namespace App\Tests\Unit\Action\Admin\Security;

use App\Action\Admin\Security\UserCreateAction;
use App\Domain\User\UserManager;
use App\Repository\TokenAwareRepository;
use Basster\LazyResponseBundle\Response\TemplateResponse;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

/**
 * Class UserCreateActionTest.
 */
final class UserCreateActionTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideInvalidTokenExceptions
     */
    public function renderInvalidTokenViewWhenTokenNotFound(\Exception $exception): void
    {
        $userCreateTokenRepository = $this->prophesize(TokenAwareRepository::class);
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $userManager = $this->createMock(UserManager::class);

        $token = 'invalid-token';

        $userCreateTokenRepository
            ->findOneByToken($token)
            ->shouldBeCalled()
            ->willThrow($exception);

        $action = new UserCreateAction($userCreateTokenRepository->reveal(), $formFactory, $userManager);
        $response = $action->__invoke($token, new Request());
        self::assertInstanceOf(TemplateResponse::class, $response);
        self::assertSame('admin/security/invalid_token.html.twig', $response->getTemplate());
    }

    public function provideInvalidTokenExceptions(): iterable
    {
        yield 'token not found' => [new NoResultException()];
        yield 'duplicated token, for some reason' => [new NonUniqueResultException()];
    }
}
