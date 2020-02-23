<?php

declare(strict_types=1);

namespace App\Action\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class DashboardAction
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/admin/dashboard", name="app_admin_dashboard")
     */
    public function __invoke(): Response
    {
        return new Response($this->twig->render('admin/dashboard.html.twig'));
    }
}