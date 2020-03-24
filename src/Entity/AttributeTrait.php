<?php

declare(strict_types=1);

namespace App\Entity;

use App\Domain\Attribute\Exception\MissingAttributeTypeException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class AttributeTrait.
 */
trait AttributeTrait
{
    /**
     * @var Collection|Attribute[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Attribute")
     */
    private Collection $attributes;

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

    /** {@inheritdoc} */
    public function getAttribute(string $type): Attribute
    {
        foreach ($this->attributes as $attribute) {
            if (strcasecmp($attribute->getType()->getTitle(), $type) === 0) {
                return $attribute;
            }
        }

        throw new MissingAttributeTypeException($type);
    }

    public function hasAttribute(string $type): bool
    {
        try {
            $this->getAttribute($type);

            return true;
        } catch (MissingAttributeTypeException $ex) {
            return false;
        }
    }

    private function initAttributes(): void
    {
        $this->attributes = new ArrayCollection();
    }
}
