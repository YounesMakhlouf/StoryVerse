<?php

namespace App\DataFixtures;

use App\Entity\Quest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $questsData = json_decode(file_get_contents(__DIR__ . '../data/quests.json'), true);

        foreach ($questsData as $questData) {
            $quest = $this->createQuest($questData['name'], $questData['type'], $questData['description'], $questData['requirement'], $questData['points'], $questData['amount']);
            $manager->persist($quest);
        }

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