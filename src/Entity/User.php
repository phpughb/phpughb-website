<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     *
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(max="200")
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\NotBlank
     */
    private ?string $encodedPassword = null;

    /**
     * @ORM\Column(type="string", length=20)
     *
     * @Assert\NotBlank
     */
    private string $role;

    public function __construct(string $email, string $role)
    {
        $this->email = $email;
        $this->role = $role;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function getPassword(): string
    {
        return $this->encodedPassword;
    }

    public function getSalt(): void
    {
        //no needed
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        //not needed
    }

    public function setEncodedPassword(string $encodedPassword): void
    {
        $this->encodedPassword = $encodedPassword;
    }

    public function __toString()
    {
        return $this->getUsername();
    }
}
