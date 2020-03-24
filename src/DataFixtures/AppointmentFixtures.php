<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\Attribute;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class AppointmentFixtures extends Fixture implements DependentFixtureInterface
{
    public const PHPUGHB_3 = 'appointment:phpughb3';

    public function load(ObjectManager $manager): void
    {
        /** @var \App\Entity\Talk $talk */
        $talk = $this->getReference(TalkFixtures::TALK_SOLID);
        /** @var \App\Entity\Location $teamNeusta */
        $teamNeusta = $this->getReference(LocationFixtures::TEAM_NEUSTA);
        $ticketsType = $this->getReference(AttributeFixtures::TYPE_TICKETS);

        $ticketsAttr = new Attribute($ticketsType, 'Anmelden', 'https://www.eventbrite.de/e/php-usergroup-bremen-phpughb-iii-tickets-93670576215');

        $appointment = new Appointment(
          '#PHPUGHB III',
          'Lorem ipsum',
          DateTimeImmutable::createFromFormat('Y-m-d H:i', '2020-03-11 18:30')
        );
        $appointment->addTalk($talk);
        $appointment->setLocation($teamNeusta);
        $appointment->addAttribute($ticketsAttr);

        $manager->persist($appointment);
        $manager->persist($ticketsAttr);

        $manager->flush();

        $this->addReference(self::PHPUGHB_3, $appointment);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            TalkFixtures::class,
            LocationFixtures::class,
            AttributeFixtures::class,
        ];
    }
}
