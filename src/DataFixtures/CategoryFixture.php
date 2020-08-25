<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $politique = new Category();
        $politique->setTitle('Politique')
            ->setAlias('politique');

        $economie = new Category();
        $economie->setTitle('Economie')
            ->setAlias('economie');

        $social = new Category();
        $social->setTitle('Social')
            ->setAlias('social');

        $sante = new Category();
        $sante->setTitle('Santé')
            ->setAlias('sante');

        $sports = new Category();
        $sports->setTitle('Sports')
            ->setAlias('sports');

        $culture = new Category();
        $culture->setTitle('Culture')
            ->setAlias('culture');

        $loisirs = new Category();
        $loisirs->setTitle('Loisirs')
            ->setAlias('loisirs');

        $manager->persist($politique);
        $manager->persist($economie);
        $manager->persist($social);
        $manager->persist($sante);
        $manager->persist($sports);
        $manager->persist($culture);
        $manager->persist($loisirs);
        $manager->flush();

        $this->addReference('categorie', $politique);

    }
}
