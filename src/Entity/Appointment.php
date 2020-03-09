<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
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
class Appointment
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
     * @Assert\Length(max="150")
     */
    private string $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $text;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $dateTime;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Talk", mappedBy="appointment")
     */
    private Collection $talks;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="appointments")
     */
    private ?Location $location = null;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Attribute")
     */
    private Collection $attributes;

    public function __construct(string $title, ?string $text = null, ?DateTimeInterface $dateTime = null)
    {
        $this->title = $title;
        $this->text = $text;
        $this->dateTime = $dateTime;
        $this->talks = new ArrayCollection();
        $this->attributes = new ArrayCollection();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDateTime(): ?DateTimeInterface
    {
        return $this->dateTime;
    }

    public function addTalk(Talk $talk): void
    {
        $this->talks->add($talk);
        $talk->setAppointment($this);
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): void
    {
        $this->location = $location;
    }

    /**
     * @return Collection|Attribute[]
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(Attribute $attribute): void
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes[] = $attribute;
        }
    }

    public function removeAttribute(Attribute $attribute): void
    {
        if ($this->attributes->contains($attribute)) {
            $this->attributes->removeElement($attribute);
        }
    }
}
