<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 *
 * @UniqueEntity(fields={"title"})
 */
class Talk
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
     * @Assert\Length(max="200")
     */
    private string $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Appointment", inversedBy="talks")
     */
    private Appointment $appointment;

    public function __construct(string $title, Appointment $appointment)
    {
        $this->title = $title;
        $this->appointment = $appointment;
    }
}
