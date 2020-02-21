<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class AddUserDto
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(max="200")
     */
    public string $email;

    public bool $isAdmin;

    public function __construct(string $email, bool $isAdmin)
    {
        $this->email = $email;
        $this->isAdmin = $isAdmin;
    }
}
