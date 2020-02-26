<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Speaker;
use App\Entity\Talk;
use PHPUnit\Framework\TestCase;

/**
 * Class SpeakerTest.
 *
 * @covers \App\Entity\Speaker
 *
 * @internal
 */
final class SpeakerTest extends TestCase
{
    /**
     * @test
     */
    public function getTalksReturnsTalkCollection(): void
    {
        $speaker = new Speaker('Ole', 'Rößner');
        $talk = new Talk('My Talk', $speaker);

        static::assertSame($talk, $speaker->getTalks()[0]);
    }
}
