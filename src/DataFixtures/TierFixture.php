<?php

namespace App\DataFixtures;

use App\Entity\Tier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TierFixture  extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tiersData = json_decode(file_get_contents(__DIR__ . '../data/tiers.json'), true);

        foreach ($tiersData as $tierData) {
            $tier = $this->createTier($tierData['name'], $tierData['xpThreshold'], $tierData['description'], $tierData['imagePath']);
            $manager->persist($tier);
        }

        $manager->flush();
    }

    private function createTier(string $name, int $xpThreshold, string $description = null, $imagePath = null): Tier
    {
        $tier = new Tier();
        $tier->setName($name);
        if ($description !== null) {
            $tier->setDescription($description);
        }
        if ($imagePath !== null) {
            $tier->setBadge($imagePath);
        }
        $tier->setXpThreshold($xpThreshold);
        return $tier;
    }
}