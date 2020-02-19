<?php

declare(strict_types=1);

namespace App\Action\Feed;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\Error;

class YoutubeAction
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @throws Error
     */
    public function __invoke(): Response
    {
        return new Response($this->twig->render('parts/youtube.html.twig'));
    }
}