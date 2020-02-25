<?php

declare(strict_types=1);

namespace App\Tests\Unit\Action\Admin\Security;

use App\Action\Admin\Security\LoginAction;
use App\Entity\User;
use Basster\LazyResponseBundle\Response\RedirectResponse;
use Basster\LazyResponseBundle\Response\TemplateResponse;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @covers \App\Action\Admin\Security\LoginAction
 *
 * @internal
 */
final class LoginActionTest extends TestCase
{
    private ObjectProphecy $authenticationUtils;
    private ObjectProphecy $tokenStorage;

    protected function setUp(): void
    {
        $this->authenticationUtils = $this->prophesize(AuthenticationUtils::class);
        $this->tokenStorage = $this->prophesize(TokenStorageInterface::class);
    }

    /**
     * @test
     */
    public function redirectToDashboardWhenTokenStorageContainsUser(): void
    {
        $expectedRouteName = 'app_admin_dashboard';

        $token = $this->prophesize(TokenInterface::class);
        $this->tokenStorage
            ->getToken()
            ->shouldBeCalled()
            ->willReturn($token->reveal())
        ;

        $token
            ->getUser()
            ->shouldBeCalled()
            ->willReturn(new User('some@email.de', 'SOME_ROLE'))
        ;

        $action = new LoginAction($this->authenticationUtils->reveal(), $this->tokenStorage->reveal());
        $response = $action->__invoke();

        static::assertInstanceOf(RedirectResponse::class, $response);
        static::assertSame($expectedRouteName, $response->getRouteName());
    }

    /**
     * @test
     */
    public function renderLoginView(): void
    {
        $expectedTemplate = 'admin/security/login.html.twig';

        $token = $this->prophesize(TokenInterface::class);
        $this->tokenStorage
            ->getToken()
            ->shouldBeCalled()
            ->willReturn($token->reveal())
        ;

        $action = new LoginAction($this->authenticationUtils->reveal(), $this->tokenStorage->reveal());
        $response = $action->__invoke();

        static::assertInstanceOf(TemplateResponse::class, $response);
        static::assertSame($expectedTemplate, $response->getTemplate());
    }
}
