<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Entity\UserCreateToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class UserCreateTokenManager
{
    private TokenGenerator $tokenGenerator;
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;
    private Environment $twig;
    private string $emailSender;
    private UserManager $userManager;

    public function __construct(
        TokenGenerator $tokenGenerator,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        Environment $twig,
        UserManager $userManager,
        string $emailSender
    ) {
        $this->tokenGenerator = $tokenGenerator;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->emailSender = $emailSender;
        $this->userManager = $userManager;
    }

    public function send(string $emailAddress, bool $isAdmin): void
    {
        $userCreateToken = new UserCreateToken(
            $this->tokenGenerator->generate(),
            $emailAddress,
            $isAdmin
        );

        $email = new Email();
        $email
            ->from($this->emailSender)
            ->to($emailAddress)
            ->subject('PHPUGHB Admin Account erstellen')
            ->text($this->twig->render('email/user_create_token.txt.twig', [
                'token' => $userCreateToken->getToken(),
            ]))
            ->html($this->twig->render('email/user_create_token.html.twig', [
                'token' => $userCreateToken->getToken(),
            ]))
        ;

        $this->mailer->send($email);

        $this->entityManager->persist($userCreateToken);
        $this->entityManager->flush($userCreateToken);
    }

    public function activate(UserCreateToken $userCreateToken, string $plainPassword): void
    {
        $this->userManager->create(
            $userCreateToken->getEmail(),
            $plainPassword,
            $userCreateToken->isAdmin()
        );
        $this->entityManager->remove($userCreateToken);
        $this->entityManager->flush();
    }
}
