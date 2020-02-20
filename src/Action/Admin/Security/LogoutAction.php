<?php

declare(strict_types=1);

namespace App\Action\Admin\Security;

use LogicException;
use Symfony\Component\Routing\Annotation\Route;

final class LogoutAction
{
    /**
     * @Route("/admin/logout", name="app_admin_logout", methods={"GET"})
     */
    public function __invoke(): void
    {
        //Logout is handled by the Authenticator, thus there has to be an action to provide the route
        throw new LogicException('Logout Controller should never be reached');
    }
}
