<?php

declare(strict_types=1);

namespace App\Action;

use Basster\LazyResponseBundle\Response\TemplateResponse;
use Symfony\Component\Routing\Annotation\Route;

final class HomeAction
{
    /**
     * @Route("/", name="app_home")
     */
    public function __invoke(): TemplateResponse
    {
        return new TemplateResponse('home.html.twig');
    }
}
