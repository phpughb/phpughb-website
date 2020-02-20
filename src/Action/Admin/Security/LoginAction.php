<?php

declare(strict_types=1);

namespace App\Action\Admin\Security;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;
use Twig\Error\Error;

final class LoginAction
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/admin/login", name="app_admin_login", methods={"GET", "POST"})
     *
     * @throws Error
     */
    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return new Response($this->twig->render('admin/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error_message' => $error,
        ]));
    }
}
