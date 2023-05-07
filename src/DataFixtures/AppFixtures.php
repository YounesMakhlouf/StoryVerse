<?php

namespace App\DataFixtures;

use App\Factory\CommentFactory;
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
        UserFactory::createMany(50, function () {
            return [
                'likedStories' => StoryFactory::randomRange(0, 10)
            ];
        });
        ContributionFactory::createMany(100, function () {
            return [
                'story' => StoryFactory::random(),
                'author' => UserFactory::random()
            ];
        });
        CommentFactory::createMany(100, function () {
            return [
                'story' => StoryFactory::random(),
                'author' => UserFactory::random()
            ];
        });
        $manager->flush();
    }
}
