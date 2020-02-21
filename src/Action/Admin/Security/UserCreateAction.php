<?php

declare(strict_types=1);

namespace App\Action\Admin\Security;

use App\Domain\User\UserManager;
use App\Form\RegisterType;
use App\Repository\UserCreateTokenRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class UserCreateAction
{
    private Environment $twig;
    private UserCreateTokenRepository $userCreateTokenRepository;
    private FormFactoryInterface $formFactory;
    private RouterInterface $router;
    private UserManager $userManager;

    public function __construct(
        Environment $twig,
        UserCreateTokenRepository $userCreateTokenRepository,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        UserManager $userManager
    ) {
        $this->twig = $twig;
        $this->userCreateTokenRepository = $userCreateTokenRepository;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->userManager = $userManager;
    }

    /**
     * @Route("/admin/nutzer-erstellen/{token}", name="app_admin_user-create", methods={"GET", "POST"})
     */
    public function __invoke(string $token, Request $request): Response
    {
        $userCreateToken = $this->userCreateTokenRepository->findOneByToken($token);
        if ($userCreateToken === null) {
            return new Response($this->twig->render('admin/security/invalid_token.html.twig'));
        }

        $form = $this->formFactory->create(RegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->getData()['password'];
            $this->userManager->create($userCreateToken->getEmail(), $password, $userCreateToken->isAdmin());

            return new RedirectResponse($this->router->generate('app_home'));
        }

        return new Response($this->twig->render('admin/security/input_token.html.twig', [
            'register_form_view' => $form->createView(),
            'email' => $userCreateToken->getEmail(),
        ]));
    }
}
