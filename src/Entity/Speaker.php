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
class Speaker
{
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

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Attribute")
     */
    private Collection $attributes;

    public function __construct(string $firstname, string $lastname)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->talks = new ArrayCollection();
        $this->attributes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function withUser(?User $user): self
    {
        $new = clone $this;
        $new->user = $user;

        return $new;
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

    public function withTalk(Talk $talk): self
    {
        $new = clone $this;
        if (!$new->talks->contains($talk)) {
            $new->talks[] = $talk;
        }

        return $new;
    }

    /**
     * @return Collection|Attribute[]
     */
    public function getAttributes(): Collection
    {
        return clone $this->attributes;
    }

    public function withAttribute(Attribute $attribute): self
    {
        $new = clone $this;
        if (!$new->attributes->contains($attribute)) {
            $new->attributes[] = $attribute;
        }

        return $new;
    }

    public function withoutAttribute(Attribute $attribute): self
    {
        $new = clone $this;
        if ($new->attributes->contains($attribute)) {
            $new->attributes->removeElement($attribute);
        }

        return $new;
    }
}
