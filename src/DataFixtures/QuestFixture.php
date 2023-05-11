<?php

namespace App\DataFixtures;

use App\Entity\Quest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Account creation
        $quest1 = $this->createQuest(
            'The Legendary Accountant',
            'Account Creator',
            'Master the ancient art of account creation on Storyverse. Create a new account and unlock the secrets of the digital realm!',
            'create_account',
            50
        );
        $manager->persist($quest1);

        // Story creation
        $quest2 = $this->createQuest(
            "The Storyteller's Genesis",
            'Story Creator',
            'Embark on a mythical journey as you breathe life into your first story on Storyverse. Let your imagination run wild and witness the birth of a new literary universe!',
            'post_stories',
            100,
            1
        );
        $manager->persist($quest2);

        $quest3 = $this->createQuest(
            "The Novice Author's Odyssey",
            'Story Creator',
            'Unleash your creative prowess and conquer the literary world by publishing not one, not two, but five epic stories on Storyverse. Prepare to become a legend among authors!',
            'post_stories',
            600,
            5
        );
        $manager->persist($quest3);

        $quest4 = $this->createQuest(
            'The Storytelling Maestro',
            'Story Creator',
            'Channel your inner storytelling genius and grace the world with ten captivating stories on Storyverse. Claim your rightful place among the gods of imagination!',
            'post_stories',
            1500,
            10
        );
        $manager->persist($quest4);

        // Contribution
        $quest5 = $this->createQuest(
            'The Word Weaver',
            'Contributor',
            'Contribute to a story on Storyverse and witness the magic unfold. Your words shall weave the fabric of storytelling!',
            'post_contributions',
            100,
            1
        );
        $manager->persist($quest5);

        $quest6 = $this->createQuest(
            'The Storyvore',
            'Contributor',
            'Become a true Storyvore by contributing to 10 stories on Storyverse. Your insatiable appetite for storytelling shall be celebrated!',
            'post_contributions',
            2000,
            10
        );
        $manager->persist($quest6);

        // Community Quests
        $quest7 = $this->createQuest(
            'The Social Butterfly',
            'Community Quest',
            'Spread your wings and become the ultimate social butterfly! Engage with the Storyverse community by leaving 10 witty comments on stories and ignite conversations that will go down in history!',
            'post_comments',
            1000,
            50
        );
        $manager->persist($quest7);

        $quest8 = $this->createQuest(
            'The Epic High-Fiver',
            'Community Quest',
            'Embrace the power of the high-five! Show your appreciation for fellow storytellers by giving 25 likes to their magnificent tales. Your mighty thumbs shall bring joy to the hearts of many!',
            'post_likes',
            1000,
            100
        );
        $manager->persist($quest8);

        // Achievement Quests
        $quest9 = $this->createQuest(
            'The Great Adventurer',
            'Achievement Quest',
            'Embark on an epic journey of exploration and discovery! Complete 3 quests of various kinds, showcasing your unwavering determination and insatiable curiosity. Only the bravest and most relentless adventurers can claim this legendary title!',
            'complete_quests',
            1000,
            3
        );
        $manager->persist($quest9);

        $quest10 = $this->createQuest(
            'The Time Traveler',
            'Achievement Quest',
            'Bend the fabric of time and rewrite history! Complete 9 quests of various kinds, showcasing your unwavering determination and insatiable curiosity. Only the bravest and most relentless adventurers can claim this legendary title!',
            'complete_quests',
            9999,
            9
        );
        $manager->persist($quest10);

        $manager->flush();
    }


    private function createQuest(string $name, string $type, string $description, string $requirement, int $points, int $amount = null): Quest
    {
        $quest = new Quest();
        $quest->setName($name);
        $quest->setType($type);
        $quest->setDescription($description);
        $quest->setRequirement($requirement);
        if ($amount !== null) {
            $quest->setAmount($amount);
        }
        $quest->setPoints($points);
        return $quest;
    }
}