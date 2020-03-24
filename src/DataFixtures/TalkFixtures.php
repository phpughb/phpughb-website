<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Attribute;
use App\Entity\Talk;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class TalkFixtures extends Fixture implements DependentFixtureInterface
{
    public const TALK_SOLID = 'talk:SOLIDe Symfony Apps';
    public const TALK_IMPORTS = 'talk:Imports like a pro';
    public const TALK_DISCO = 'talk:Disco - A fresh look at DI';

    public function load(ObjectManager $manager): void
    {
        $solid = new Talk('SOLIDe Symfony Apps', $this->getReference(SpeakerFixture::OLE));
        $imports = new Talk('Imports like a pro', $this->getReference(SpeakerFixture::DENIS));
        $disco = new Talk('Disco - A fresh look at DI', $this->getReference(SpeakerFixture::STEPHAN));

        $importsVideo = new Attribute(
          $this->getReference(AttributeFixtures::TYPE_YOUTUBE),
          'Ansehen',
          'https://youtu.be/tgb5kybJISM'
        );
        $imports->addAttribute($importsVideo);

        $manager->persist($solid);
        $manager->persist($imports);
        $manager->persist($importsVideo);
        $manager->persist($disco);

        $manager->flush();

        $this->addReference(self::TALK_SOLID, $solid);
        $this->addReference(self::TALK_IMPORTS, $imports);
        $this->addReference(self::TALK_DISCO, $disco);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [SpeakerFixture::class];
    }
}
