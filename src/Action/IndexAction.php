<?php

declare(strict_types=1);

namespace App\Action;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\Error;

class IndexAction
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="app_index")
     *
     * @throws Error
     */
    public function __invoke()
    {
        return new Response($this->twig->render('base.html.twig'));
    }
}