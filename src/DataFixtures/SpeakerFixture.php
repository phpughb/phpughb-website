<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Attribute;
use App\Entity\Speaker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class SpeakerFixturea.
 */
final class SpeakerFixture extends Fixture implements DependentFixtureInterface
{
    public const OLE = 'speaker:ole';
    public const DENIS = 'speaker:denis';
    public const STEPHAN = 'speaker:stephan';

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $twitterType = $this->getReference(AttributeFixtures::TYPE_TWITTER);

        $ole = new Speaker('Ole', 'Rößner');
        $oleTwitter = new Attribute($twitterType, 'djbasster');

        $ole->addAttribute($oleTwitter);
        $ole->linkUser($this->getReference(UserFixture::USER_OLE));

        $denis = new Speaker('Denis', 'Brumann');
        $denisTwitter = new Attribute($twitterType, 'dbrumann');

        $stephan = new Speaker('Stephan', 'Hochdörfer');
        $stephanTwitter = new Attribute($twitterType, 'shochdoerfer');

        $manager->persist($ole);
        $manager->persist($oleTwitter);
        $manager->persist($denis);
        $manager->persist($denisTwitter);
        $manager->persist($stephan);
        $manager->persist($stephanTwitter);
        $manager->flush();

        $this->setReference(self::OLE, $ole);
        $this->setReference(self::DENIS, $denis);
        $this->setReference(self::STEPHAN, $stephan);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            AttributeFixtures::class,
            UserFixture::class,
        ];
    }
}
