<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Attribute;
use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class LocationFixtures.
 */
final class LocationFixtures extends Fixture implements DependentFixtureInterface
{
    public const TEAM_NEUSTA = 'location:team-neusta';

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $latLngType = $this->getReference(AttributeFixtures::TYPE_LATLNG);

        $teamNeusta = new Location('team neusta', 'Konsul-Smidt-Str.', '24', '28217', 'Bremen');
        $tnLatLng = new Attribute($latLngType, '8.7742443,53.090925');
        $teamNeusta->addAttribute($tnLatLng);

        $manager->persist($teamNeusta);
        $manager->persist($tnLatLng);
        $manager->flush();

        $this->setReference(self::TEAM_NEUSTA, $teamNeusta);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            AttributeFixtures::class,
        ];
    }
}
