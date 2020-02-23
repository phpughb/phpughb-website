<?php

declare(strict_types=1);

namespace App\Entity;

use App\Collection\TalkCollection;
use DateTime;
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
     * @ORM\Column(type="string", length=2000, nullable=true)
     *
     * @Assert\Length(max="2000")
     */
    private ?string $text;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $dateTime;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Talk", mappedBy="appointment")
     */
    private TalkCollection $talks;

    public function __construct(string $title, ?string $text = null, ?DateTime $dateTime = null, array $talks = [])
    {
        $this->title = $title;
        $this->text = $text;
        $this->dateTime = $dateTime;
        $this->talks = new TalkCollection(...$this->talks);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDateTime(): ?DateTime
    {
        return $this->dateTime;
    }
}
