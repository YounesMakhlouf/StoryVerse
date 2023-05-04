<?php

namespace App\DataFixtures;
use App\Factory\ContributionFactory;
use App\Factory\GenreFactory;
use App\Factory\StoryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        GenreFactory::createMany(100);
        StoryFactory::createMany(25, function () {
           return [
                'genre' => GenreFactory::randomRange(1,3)
            ];
        });
        ContributionFactory::createMany(100);
        $manager->flush();
    }
}
