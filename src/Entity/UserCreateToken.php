<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 *
 * @UniqueEntity(fields={"token"})
 * @UniqueEntity(fields={"email"})
 */
class UserCreateToken
{
    /**
     * @ORM\Column(type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     */
    private string $token;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isAdmin;

    public function __construct(string $token, string $email, bool $isAdmin)
    {
        $this->token = $token;
        $this->email = $email;
        $this->isAdmin = $isAdmin;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
