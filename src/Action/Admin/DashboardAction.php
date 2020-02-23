<?php

declare(strict_types=1);

namespace App\Action\Admin;

use Basster\LazyResponseBundle\Response\TemplateResponse;
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
    public function __invoke(): TemplateResponse
    {
        return new TemplateResponse('admin/dashboard.html.twig');
    }
}
