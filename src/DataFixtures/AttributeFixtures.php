<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\AttributeType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AttributeFixtures.
 */
final class AttributeFixtures extends Fixture
{
    public const TYPE_TWITTER = 'attribute_type:twitter';
    public const TYPE_YOUTUBE = 'attribute_type:youtube';

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $twitterType = new AttributeType('twitter', 'twitter');
        $youtubeType = new AttributeType('youtube', 'youtube');

        $manager->persist($twitterType);
        $manager->persist($youtubeType);
        $manager->flush();

        $this->addReference(self::TYPE_TWITTER, $twitterType);
        $this->addReference(self::TYPE_YOUTUBE, $youtubeType);
    }
}
