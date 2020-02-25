<?php

declare(strict_types=1);

namespace App\Action\Admin\Security;

use App\Domain\User\UserCreateTokenManager;
use App\Form\RegisterType;
use App\Repository\TokenAwareRepository;
use Basster\LazyResponseBundle\Response\LazyResponseInterface;
use Basster\LazyResponseBundle\Response\RedirectResponse;
use Basster\LazyResponseBundle\Response\TemplateResponse;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class UserCreateAction
{
    private TokenAwareRepository $userCreateTokenRepository;
    private FormFactoryInterface $formFactory;
    private UserCreateTokenManager $userCreateTokenManager;

    public function __construct(
        TokenAwareRepository $userCreateTokenRepository,
        FormFactoryInterface $formFactory,
        UserCreateTokenManager $userCreateTokenManager
    ) {
        $this->userCreateTokenRepository = $userCreateTokenRepository;
        $this->formFactory = $formFactory;
        $this->userCreateTokenManager = $userCreateTokenManager;
    }

    /**
     * @Route("/admin/nutzer-erstellen/{token}", name="app_admin_user-create", methods={"GET", "POST"})
     */
    public function __invoke(string $token, Request $request): LazyResponseInterface
    {
        try {
            $userCreateToken = $this->userCreateTokenRepository->findOneByToken($token);
        } catch (NoResultException | NonUniqueResultException $ex) {
            return new TemplateResponse('admin/security/invalid_token.html.twig');
        }

        $form = $this->formFactory->create(RegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->getData()['password'];
            $this->userCreateTokenManager->activate($userCreateToken, $password);

            return new RedirectResponse('app_home');
        }

        return new TemplateResponse('admin/security/input_token.html.twig', [
            'register_form_view' => $form->createView(),
            'email' => $userCreateToken->getEmail(),
        ]);
    }
}
