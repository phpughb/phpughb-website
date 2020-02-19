<?php

declare(strict_types=1);

namespace App\Action;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\Error;

class DataUsageAction
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/datenschutz", name="app_data-usage")
     *
     * @throws Error
     */
    public function __invoke(): Response
    {
        return new Response($this->twig->render('data_usage.html.twig'));
    }
}
