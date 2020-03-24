<?php

declare(strict_types=1);

namespace App\Domain\Attribute\Exception;

use RuntimeException;
use Throwable;

/**
 * Class MissingAttributeException.
 */
final class MissingAttributeTypeException extends RuntimeException
{
    public function __construct(string $missingType, int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Missing attribute type {$missingType}", $code, $previous);
    }
}
