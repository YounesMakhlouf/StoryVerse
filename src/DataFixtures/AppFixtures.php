<?php

namespace App\DataFixtures;
use App\Factory\StoryFactory;
use App\Factory\UserFactory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(25);
        StoryFactory::createMany(25);

        $manager->flush();
    }
}
