<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?Appointment $appointment = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Speaker", inversedBy="talks")
     * @ORM\JoinColumn(nullable=false)
     */
    private Speaker $speaker;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Attribute")
     */
    private Collection $attributes;

    public function __construct(string $title, Speaker $speaker)
    {
        $this->title = $title;
        $this->speaker = $speaker;
        $speaker->addTalk($this);
        $this->attributes = new ArrayCollection();
    }

    public function getSpeaker(): ?Speaker
    {
        return $this->speaker;
    }

    public function setAppointment(Appointment $appointment): self
    {
        $this->appointment = $appointment;

        return $this;
    }

    /**
     * @return Collection|Attribute[]
     */
    public function getAttributes(): Collection
    {
        return clone $this->attributes;
    }

    public function addAttribute(Attribute $attribute): self
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes[] = $attribute;
        }

        return $this;
    }

    public function removeAttribute(Attribute $attribute): self
    {
        if ($this->attributes->contains($attribute)) {
            $this->attributes->removeElement($attribute);
        }

        return $this;
    }
}
