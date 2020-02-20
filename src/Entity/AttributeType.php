<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class AttributeType
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=10)
     *
     * @Assert\NotBlank
     * @Assert\Length(max="10")
     */
    private string $faIcon;

    /**
     * @ORM\Column(type="string", length=200)
     *
     * @Assert\NotBlank
     * @Assert\Length(max="200")
     */
    private string $title;

    public function __construct(string $faIcon, string $title)
    {
        $this->faIcon = $faIcon;
        $this->title = $title;
    }
}
