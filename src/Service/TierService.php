<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\TierRepository;

class TierService
{
    private TierRepository $tierRepository;

    public function __construct(TierRepository $tierRepository)
    {
        $this->tierRepository = $tierRepository;
    }

    public function updateTierForUser(User $user): void
    {
        $tiers = $this->tierRepository->findAllOrderedByXpThresholdAscending();
        $currentTier = $user->getTier();

        if (!$currentTier) {
            $user->setTier($tiers[0]);
            $currentTier = $tiers[0];
        }

        $userXp = $user->getXp();
        $nextTier = null;

        foreach ($tiers as $tier) {
            if ($tier->getXpThreshold() > $userXp) {
                break;
            }
            $nextTier = $tier;
        }

        if ($nextTier && $nextTier !== $currentTier) {
            $user->setTier($nextTier);
        }
    }
}