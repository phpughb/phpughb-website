<?php

declare(strict_types=1);

namespace App\Domain\User;

class TokenGenerator
{
    private const ALLOWED_CHARACTERS = 'abcdefghijklmnopqrstuvwxyz0123456789';

    public function generate(int $length = 30, string $allowedChars = self::ALLOWED_CHARACTERS): string
    {
        $random = '';
        $chars = str_split($allowedChars);
        $allowedCharsLength = count($chars);
        for ($i = 0; $i < $length; ++$i) {
            $index = random_int(0, $allowedCharsLength - 1);
            $random .= $allowedChars[$index];
        }

        return $random;
    }
}
