<?php

declare(strict_types=1);

namespace App\Tests\Unit\Action\Admin\Security;

use App\Action\Admin\Security\UserCreateAction;
use App\Domain\User\UserCreateTokenManager;
use App\Entity\UserCreateToken;
use App\Form\RegisterType;
use App\Repository\TokenAwareRepository;
use Basster\LazyResponseBundle\Response\RedirectResponse;
use Basster\LazyResponseBundle\Response\TemplateResponse;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \App\Action\Admin\Security\UserCreateAction
 */
final class UserCreateActionTest extends TestCase
{
    private ObjectProphecy $userCreateTokenRepository;
    private ObjectProphecy $formFactory;
    private ObjectProphecy $userCreateTokenManager;

    protected function setUp(): void
    {
        $this->userCreateTokenRepository = $this->prophesize(TokenAwareRepository::class);
        $this->formFactory = $this->prophesize(FormFactoryInterface::class);
        $this->userCreateTokenManager = $this->prophesize(UserCreateTokenManager::class);
    }

    /**
     * @test
     * @dataProvider provideInvalidTokenExceptions
     */
    public function renderInvalidTokenViewWhenTokenNotFound(\Exception $exception): void
    {
        $token = 'invalid-token';
        $invalidTokenTemplate = 'admin/security/invalid_token.html.twig';

        $this->userCreateTokenRepository
            ->findOneByToken($token)
            ->shouldBeCalled()
            ->willThrow($exception);

        $action = new UserCreateAction(
            $this->userCreateTokenRepository->reveal(),
            $this->formFactory->reveal(),
            $this->userCreateTokenManager->reveal()
        );
        $response = $action->__invoke($token, new Request());

        self::assertInstanceOf(TemplateResponse::class, $response);
        self::assertSame($invalidTokenTemplate, $response->getTemplate());
    }

    public function provideInvalidTokenExceptions(): iterable
    {
        yield 'token not found' => [new NoResultException()];
        yield 'duplicated token, for some reason' => [new NonUniqueResultException()];
    }

    /**
     * @test
     */
    public function createUserAndRedirectToHomeWhenFormIsSubmittedAndValid(): void
    {
        $userCreateToken = new UserCreateToken('some-token', 'some@email.de', false);
        $this->userCreateTokenRepository
            ->findOneByToken($userCreateToken->getToken())
            ->shouldBeCalled()
            ->willReturn($userCreateToken);

        $form = $this->prophesize(FormInterface::class);
        $this->formFactory
            ->create(RegisterType::class)
            ->shouldBeCalled()
            ->willReturn($form->reveal());

        $request = new Request();
        $form
            ->handleRequest($request)
            ->shouldBeCalled();
        $form
            ->isSubmitted()
            ->shouldBeCalled()
            ->willReturn(true);
        $form
            ->isValid()
            ->shouldBeCalled()
            ->willReturn(true);

        $data = ['password' => 'some-password'];
        $form
            ->getData()
            ->shouldBeCalled()
            ->willReturn($data);

        $this->userCreateTokenManager
            ->activate($userCreateToken, $data['password'])
            ->shouldBeCalled();

        $action = new UserCreateAction(
            $this->userCreateTokenRepository->reveal(),
            $this->formFactory->reveal(),
            $this->userCreateTokenManager->reveal()
        );
        $response = $action->__invoke($userCreateToken->getToken(), $request);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('app_home', $response->getRouteName());
    }

    /**
     * @test
     */
    public function renderInputTokenViewWhenTokenWasValid(): void
    {
        $expectedTemplate = 'admin/security/input_token.html.twig';
        $userCreateToken = new UserCreateToken('some-token', 'some@email.de', false);
        $this->userCreateTokenRepository
            ->findOneByToken($userCreateToken->getToken())
            ->shouldBeCalled()
            ->willReturn($userCreateToken);

        $form = $this->prophesize(FormInterface::class);
        $this->formFactory
            ->create(RegisterType::class)
            ->shouldBeCalled()
            ->willReturn($form->reveal());

        $request = new Request();
        $form
            ->handleRequest($request)
            ->shouldBeCalled();
        $form
            ->isSubmitted()
            ->shouldBeCalled()
            ->willReturn(false);

        $formView = $this->prophesize(FormView::class);
        $formViewDummy = $formView->reveal();
        $form
            ->createView()
            ->shouldBeCalled()
            ->wilLReturn($formViewDummy);

        $action = new UserCreateAction(
            $this->userCreateTokenRepository->reveal(),
            $this->formFactory->reveal(),
            $this->userCreateTokenManager->reveal()
        );
        $response = $action->__invoke($userCreateToken->getToken(), $request);

        self::assertInstanceOf(TemplateResponse::class, $response);
        self::assertSame($expectedTemplate, $response->getTemplate());
        self::assertSame([
            'register_form_view' => $formViewDummy,
            'email' => $userCreateToken->getEmail(),
        ], $response->getData());
    }
}
