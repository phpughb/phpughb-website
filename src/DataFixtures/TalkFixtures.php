<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Speaker;
use App\Entity\Talk;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TalkFixtures extends Fixture implements DependentFixtureInterface
{
    public const SPEAKER_OLE = 'speaker:Ole';
    public const TALK_SOLID = 'talk:SOLIDe Symfony Apps';

    public function load(ObjectManager $manager)
    {
        /** @var \App\Entity\User $oleUser */
        $oleUser = $this->getReference(UserFixture::USER_OLE);

        $speaker = (new Speaker('Ole', 'Rößner'))
            ->setTwitter('djbasster')
            ->linkUser($oleUser)
          ;

        $talk = new Talk('SOLIDe Symfony Apps', $speaker);

        $manager->persist($speaker);
        $manager->persist($talk);

        $manager->flush();

        $this->addReference(self::SPEAKER_OLE, $speaker);
        $this->addReference(self::TALK_SOLID, $talk);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [UserFixture::class];
    }
}
