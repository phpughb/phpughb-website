<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=200, unique=true)
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
    private ?string $encryptedPassword = null;

    /**
     * @ORM\Column(type="array")
     */
    private array $roles;

    public function __construct(string $email, array $roles = [])
    {
        $this->email = $email;
        $this->roles = $roles;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): string
    {
        return $this->encryptedPassword;
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
}
