<?php

declare(strict_types=1);

namespace App\Action;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\Error;

class AboutUsAction
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/ueber-uns", name="app_about-us")
     *
     * @throws Error
     */
    public function __invoke(): Response
    {
        return new Response($this->twig->render('about_us.html.twig'));
    }
}
