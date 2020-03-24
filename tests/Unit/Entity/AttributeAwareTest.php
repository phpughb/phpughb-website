<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Domain\Attribute\Exception\MissingAttributeTypeException;
use App\Entity\Appointment;
use App\Entity\Attribute;
use App\Entity\AttributeType;
use PHPUnit\Framework\TestCase;

/**
 * Class AppointmentTest.
 *
 * @covers \App\Entity\AttributeTrait
 *
 * @internal
 */
final class AttributeAwareTest extends TestCase
{
    /**
     * @test
     */
    public function throwMissingAttributeException(): void
    {
        $this->expectException(MissingAttributeTypeException::class);

        $appointment = new Appointment('Test Appointment');
        $appointment->getAttribute('ticket');
    }

    /**
     * @test
     */
    public function getAttribute(): void
    {
        $twitterType = new AttributeType('twitter', 'twitter');
        $twitterAttr = new Attribute($twitterType, 'phpughb');

        $appointment = new Appointment('Test Appointment');
        $appointment->addAttribute($twitterAttr);

        static::assertSame($twitterAttr, $appointment->getAttribute('twitter'));
    }

    /**
     * @test
     */
    public function hasAttribute(): void
    {
        $appointment = new Appointment('Test Appointment');
        static::assertFalse($appointment->hasAttribute('twitter'));
    }
}
