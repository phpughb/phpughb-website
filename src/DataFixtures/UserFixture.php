<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public const USER_OLE = 'user:Ole';

    public function load(ObjectManager $manager): void
    {
        $user = new User('ole@phpughb.de', 'ROLE_ADMIN');
        $user->setEncodedPassword('<password>');

        $manager->persist($user);

        $manager->flush();

        $this->addReference(self::USER_OLE, $user);
    }
}
