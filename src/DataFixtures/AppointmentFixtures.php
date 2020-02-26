<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Appointment;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AppointmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var \App\Entity\Talk $talk */
        $talk = $this->getReference(TalkFixtures::TALK_SOLID);

        $appointment = new Appointment(
          '#PHPUGHB III',
          'Lorem ipsum',
          DateTimeImmutable::createFromFormat('Y-m-d H:i', '2020-03-11 18:30')
        );
        $appointment->addTalk($talk);

        $manager->persist($appointment);

        $manager->flush();

        $this->addReference('appointment:phpughb3', $appointment);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [TalkFixtures::class];
    }
}
