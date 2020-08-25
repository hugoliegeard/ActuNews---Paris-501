<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 20; $i++) {
            $post = new Post();
            $post->setTitle('Article '.$i)
                ->setAlias('article-'.$i)
                ->setImage('demo.jpg')
                ->setContent('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum error est molestiae sunt voluptate! Enim ex fugiat illum incidunt iure laudantium nostrum, praesentium temporibus veniam voluptatem? Rem repudiandae tempore voluptas.</p>')
                ->setCreatedAt(new \DateTime())
                ->setUser($this->getReference('user'))
                ->setCategory($this->getReference('categorie'));

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixture::class,
            CategoryFixture::class,
        );
    }
}
