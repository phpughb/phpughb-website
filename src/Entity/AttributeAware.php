<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Class AttributeAware.
 */
interface AttributeAware
{
    public function getAttributes(): Collection;

    public function addAttribute(Attribute $attribute): void;

    public function removeAttribute(Attribute $attribute): void;

    /**
     * @throws \App\Domain\Attribute\Exception\MissingAttributeTypeException
     */
    public function getAttribute(string $type): Attribute;

    public function hasAttribute(string $type): bool;
}
