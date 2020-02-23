<?php

declare(strict_types=1);

namespace App\Tests\Unit\Action\Admin\Security;

use App\Action\Admin\Security\UserCreateAction;
use App\Domain\User\UserManager;
use App\Repository\TokenAwareRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
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
        $twig = $this->prophesize(Environment::class);
        $userCreateTokenRepository = $this->prophesize(TokenAwareRepository::class);
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $router = $this->createMock(RouterInterface::class);
        $userManager = $this->createMock(UserManager::class);

        $token = 'invalid-token';
        $body = '<html />';

        $userCreateTokenRepository
          ->findOneByToken($token)
          ->shouldBeCalled()
          ->willThrow($exception);

        $twig->render('admin/security/invalid_token.html.twig')
             ->shouldBeCalled()
             ->willReturn($body);

        $action = new UserCreateAction($twig->reveal(), $userCreateTokenRepository->reveal(), $formFactory, $router,
          $userManager);
        $response = $action->__invoke($token, new Request());
        self::assertSame($body, $response->getContent());
    }

    public function provideInvalidTokenExceptions(): iterable
    {
        yield 'token not found' => [new NoResultException()];
        yield 'duplicated token, for some reason' => [new NonUniqueResultException()];
    }
}
