<?php

namespace App\DataFixtures;

use App\Entity\Quest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Account creation - 100 points
        $quest1 = new Quest();
        $quest1->setName('Account Creation');
        $quest1->setType('Account Creator');
        $quest1->setDescription('Create a new account on Storyverse');
        $quest1->setRequirement('create_account');
        $quest1->setPoints(100);
        $manager->persist($quest1);

        // Story creation - 50 points
        $quest2 = new Quest();
        $quest2->setName('Story Creation');
        $quest2->setType('Story Creator');
        $quest2->setDescription('Create your first story on Storyverse! Hurrayyyy');
        $quest2->setRequirement('post_stories');
        $quest2->setAmount(1);
        $quest2->setPoints(50);
        $manager->persist($quest2);

        // Story creation - 200 points
        $quest3 = new Quest();
        $quest3->setName('Novice Author');
        $quest3->setType('Story Creator');
        $quest3->setDescription('Write and publish 5 stories on Storyverse');
        $quest3->setRequirement('post_stories');
        $quest3->setAmount(5);
        $quest3->setPoints(200);
        $manager->persist($quest3);

        // Story creation - 500 points
        $quest4 = new Quest();
        $quest4->setName('Story Creation Master');
        $quest4->setType('Story Creator');
        $quest4->setDescription('Create 10 on Storyverse! You rock');
        $quest4->setRequirement('post_stories');
        $quest4->setAmount(10);
        $quest4->setPoints(500);
        $manager->persist($quest4);

        // Story completion - 100 points
        $quest5 = new Quest();
        $quest5->setName('Story Completion');
        $quest5->setType('Story Reader');
        $quest5->setDescription('Complete a story on Storyverse');
        $quest5->setRequirement('post_contributions');
        $quest5->setAmount(10);
        $quest5->setPoints(100);
        $manager->persist($quest5);

        // Story completion - 100 points
        $quest6 = new Quest();
        $quest6->setName('Bookworm');
        $quest6->setType('Story Reader');
        $quest6->setDescription('Complete a story on Storyverse');
        $quest6->setRequirement('post_contributions');
        $quest6->setAmount(10);
        $quest6->setPoints(100);
        $manager->persist($quest6);

        $manager->flush();
    }
}
