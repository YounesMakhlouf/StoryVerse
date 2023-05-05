<?php

namespace App\DataFixtures;
use App\Factory\ContributionFactory;
use App\Factory\StoryFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        StoryFactory::createMany(25);
        UserFactory::createMany(50);
//        ContributionFactory::createMany(100);
        $manager->flush();
    }
}
