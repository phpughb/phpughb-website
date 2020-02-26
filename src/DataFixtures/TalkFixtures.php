<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Attribute;
use App\Entity\AttributeType;
use App\Entity\Speaker;
use App\Entity\Talk;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TalkFixtures extends Fixture implements DependentFixtureInterface
{
    public const SPEAKER_OLE = 'speaker:Ole';
    public const TALK_SOLID = 'talk:SOLIDe Symfony Apps';

    public function load(ObjectManager $manager):void
    {
        /** @var \App\Entity\User $oleUser */
        $oleUser = $this->getReference(UserFixture::USER_OLE);

        $twitterType = new AttributeType('twitter', 'twitter');
        $twitterAttr = new Attribute($twitterType, 'djbasster');

        $speaker = (new Speaker('Ole', 'Rößner'))
            ->withAttribute($twitterAttr)
            ->withUser($oleUser)
          ;

        $talk = new Talk('SOLIDe Symfony Apps', $speaker);

        $manager->persist($twitterType);
        $manager->persist($twitterAttr);
        $manager->persist($speaker);
        $manager->persist($talk);

        $manager->flush();

        $this->addReference(self::SPEAKER_OLE, $speaker);
        $this->addReference(self::TALK_SOLID, $talk);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies():array
    {
        return [UserFixture::class];
    }
}
