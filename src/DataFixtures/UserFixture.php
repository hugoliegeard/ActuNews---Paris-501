<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstname('Hugo')
            ->setLastname('LIEGEARD')
            ->setEmail('hugo@actu.news')
            ->setPassword('test')
            ->setRoles(['ROLE_JOURNALIST'])
            ->setCreatedAt(new \DateTime());

        $manager->persist($user);
        $manager->flush();

        $this->addReference('user', $user);
    }
}
