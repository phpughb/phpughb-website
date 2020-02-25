<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class FormLoginAuthenticator extends AbstractFormLoginAuthenticator
{
    private const LOGIN_ROUTE = 'app_admin_login';
    private const POST_USERNAME = 'username';
    private const POST_PASSWORD = 'password';

    private RouterInterface $router;
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(RouterInterface $router, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->router = $router;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function supports(Request $request): bool
    {
        $routeCorrect = $request->attributes->get('_route') === self::LOGIN_ROUTE;
        $isPost = $request->isMethod('POST');

        return $routeCorrect && $isPost;
    }

    public function getCredentials(Request $request): Credentials
    {
        if (!$request->request->has(self::POST_USERNAME) || !$request->request->has(self::POST_PASSWORD)) {
            throw new CustomUserMessageAuthenticationException('Nutzername oder Passwort fehlt');
        }

        $username = $request->request->get(self::POST_USERNAME);
        $password = $request->request->get(self::POST_PASSWORD);

        return new Credentials($username, $password);
    }

    /**
     * @param Credentials $credentials
     */
    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        return $userProvider->loadUserByUsername($credentials->username);
    }

    /**
     * @param Credentials $credentials
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->userPasswordEncoder->isPasswordValid($user, $credentials->password);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): RedirectResponse
    {
        return new RedirectResponse($this->router->generate('app_admin_dashboard'));
    }

    protected function getLoginUrl(): string
    {
        return $this->router->generate('app_admin_login');
    }
}
