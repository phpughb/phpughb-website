<?php

declare(strict_types=1);

namespace App\Entity;

use App\Collection\TalkCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Speaker implements AttributeAware
{
    use AttributeTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     */
    private ?User $user = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $lastname;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Talk", mappedBy="speaker", orphanRemoval=true)
     */
    private Collection $talks;

    public function __construct(string $firstname, string $lastname)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->talks = new ArrayCollection();
        $this->initAttributes();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function linkUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getFullname(): string
    {
        return sprintf(
          '%s %s', $this->getFirstname(), $this->getLastname()
        );
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getTalks(): TalkCollection
    {
        return new TalkCollection(...$this->talks->toArray());
    }

    public function addTalk(Talk $talk): void
    {
        if (!$this->talks->contains($talk)) {
            $this->talks[] = $talk;
        }
    }

    public function __toString(): string
    {
        return sprintf('%s %s', $this->firstname, $this->lastname);
    }
}
