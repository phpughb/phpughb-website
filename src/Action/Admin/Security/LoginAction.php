<?php

declare(strict_types=1);

namespace App\Action\Admin\Security;

use App\Entity\User;
use Basster\LazyResponseBundle\Response\LazyResponseInterface;
use Basster\LazyResponseBundle\Response\RedirectResponse;
use Basster\LazyResponseBundle\Response\TemplateResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginAction
{
    private AuthenticationUtils $authenticationUtils;
    private TokenStorageInterface $tokenStorage;

    public function __construct(
        AuthenticationUtils $authenticationUtils,
        TokenStorageInterface $tokenStorage
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/admin/login", name="app_admin_login", methods={"GET", "POST"})
     */
    public function __invoke(): LazyResponseInterface
    {
        if ($this->tokenStorage->getToken()->getUser() instanceof User) {
            return new RedirectResponse('app_admin_dashboard');
        }

        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return new TemplateResponse('admin/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error_message' => $error,
        ]);
    }
}
