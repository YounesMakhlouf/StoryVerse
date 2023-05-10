<?php

namespace App\DataFixtures;

use App\Entity\Tier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TierFixture  extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tier1 = $this->createTier("Story Seeker", 0, "Embarking on a journey to discover captivating stories. Your adventure begins!", "story-seeker-badge.webp");
        $tier2 = $this->createTier("Novel Navigator", 500, "Navigating through the vast realms of novels. Every chapter is a new discovery.", "novel-navigator-badge.webp");
        $tier3 = $this->createTier("Epic Tale Teller", 2000, "Weaving epic tales with your imagination. Legends are brought to life through your words.", "epic-tale-teller-badge.webp");
        $tier4 = $this->createTier("Myth Master", 5000, "Mastering the ancient myths and legends. The secrets of the past are yours to reveal.", "myth-master-badge.webp");
        $tier5 = $this->createTier("Literary Legend", 10000, "Ascending to the realm of literary legends. Your name shall forever be etched in the annals of storytelling.", "literary-legend-badge.webp");

        $manager->persist($tier1);
        $manager->persist($tier2);
        $manager->persist($tier3);
        $manager->persist($tier4);
        $manager->persist($tier5);

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