<?php

declare(strict_types=1);

namespace App\Action\Admin\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;
use Twig\Error\Error;

final class LoginAction
{
    private Environment $twig;
    private AuthenticationUtils $authenticationUtils;
    private RouterInterface $router;
    private TokenStorageInterface $tokenStorage;

    public function __construct(
        Environment $twig,
        AuthenticationUtils $authenticationUtils,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router
    ) {
        $this->twig = $twig;
        $this->authenticationUtils = $authenticationUtils;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/admin/login", name="app_admin_login", methods={"GET", "POST"})
     *
     * @throws Error
     */
    public function __invoke(): Response
    {
        if ($this->tokenStorage->getToken()->getUser() instanceof User) {
            return new RedirectResponse($this->router->generate('app_admin_dashboard'));
        }

        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return new Response($this->twig->render('admin/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error_message' => $error,
        ]));
    }
}
