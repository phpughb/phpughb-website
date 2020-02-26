<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Attribute
{
    /**
     * @ORM\Column(type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     *
     * @Assert\NotBlank
     * @Assert\Length(max="100")
     */
    private string $value;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     *
     * @Assert\Url
     * @Assert\Length(max="1000")
     */
    private ?string $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AttributeType")
     * @ORM\JoinColumn(nullable=false)
     */
    private AttributeType $type;

    public function __construct(AttributeType $type, string $value, ?string $url = null)
    {
        $this->type = $type;
        $this->value = $value;
        $this->url = $url;
    }

    public function getType(): AttributeType
    {
        return $this->type;
    }
}
