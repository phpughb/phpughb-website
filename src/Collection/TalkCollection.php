<?php

declare(strict_types=1);

namespace App\Collection;

use App\Entity\Talk;
use Scriptibus\AbstractCollection\AbstractCollection;

class TalkCollection extends AbstractCollection
{
    public function __construct(Talk ...$talks)
    {
        parent::__construct(...$talks);
    }

    public function offsetGet($offset): Talk
    {
        return parent::offsetGet($offset);
    }

    public function current(): Talk
    {
        return parent::current();
    }

    protected static function getClass(): string
    {
        return Talk::class;
    }
}
