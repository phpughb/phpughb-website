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
class Talk implements AttributeAware
{
    use AttributeTrait;

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
    private ?Appointment $appointment = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Speaker", inversedBy="talks")
     * @ORM\JoinColumn(nullable=false)
     */
    private Speaker $speaker;

    public function __construct(string $title, Speaker $speaker)
    {
        $this->title = $title;
        $this->speaker = $speaker;
        $speaker->addTalk($this);
        $this->initAttributes();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpeaker(): ?Speaker
    {
        return $this->speaker;
    }

    public function setAppointment(Appointment $appointment): void
    {
        $this->appointment = $appointment;
    }

    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
