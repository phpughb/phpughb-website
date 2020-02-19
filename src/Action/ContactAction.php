<?php

declare(strict_types=1);

namespace App\Action;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\Error;

class ContactAction
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/kontakt", name="app_contact")
     *
     * @throws Error
     */
    public function __invoke(): Response
    {
        return new Response($this->twig->render('contact.html.twig'));
    }
}
