<?php

namespace App\Service;

use App\Entity\Quest;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;

class QuestCompletionService
{
    private ObjectRepository|EntityRepository $questRepository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->questRepository = $entityManager->getRepository(Quest::class);
    }

    public function checkQuestsForCompletion(User $user): void
    {
        $quests = $this->questRepository->findAll();

        foreach ($quests as $quest) {
            if (!$user->getCompletedQuests()->contains($quest)) {
                $this->completeQuest($user, $quest);
            }
        }
    }

    public function completeQuest(User $user, Quest $quest): void
    {
        // Check if the user has already completed the quest
        if ($user->getCompletedQuests()->contains($quest)) {
            return;
        }

        $requirement = $quest->getRequirement();
        $amount = $quest->getAmount();

        switch ($requirement) {
            case 'post_comments':
                if ($this->postCommentsCheck($user, $amount)) {
                    $this->markQuestAsCompleted($user, $quest);
                }
                break;

            case 'receive_comments':
                if ($this->receiveCommentsCheck($user, $amount)) {
                    $this->markQuestAsCompleted($user, $quest);
                }
                break;

            case 'post_likes':
                if ($this->postLikesCheck($user, $amount)) {
                    $this->markQuestAsCompleted($user, $quest);
                }
                break;

            case 'receive_likes':
                if ($this->receiveLikesCheck($user, $amount)) {
                    $this->markQuestAsCompleted($user, $quest);
                }
                break;

            case 'post_stories':
                if ($this->postStoriesCheck($user, $amount)) {
                    $this->markQuestAsCompleted($user, $quest);
                }
                break;

            case 'post_contributions':
                if ($this->postContributionsCheck($user, $amount)) {
                    $this->markQuestAsCompleted($user, $quest);
                }
                break;

            case 'daily_login':
                if ($this->verifyDailyLogin($user)) {
                    $this->markQuestAsCompleted($user, $quest);
                }
                break;
        }
    }

    private function postCommentsCheck(User $user, int $amount): bool
    {
        $commentCount = count($user->getComments());
        return ($commentCount >= $amount);
    }

    private function markQuestAsCompleted(User $user, Quest $quest): void
    {
        $user->addCompletedQuest($quest);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function receiveCommentsCheck(User $user, int $amount): bool
    {
        $commentCount = $user->getCommentsReceivedCount();
        return ($commentCount >= $amount);
    }

    private function postLikesCheck(User $user, int $amount): bool
    {
        $likesCount = count($user->getLikedStories());
        return ($likesCount >= $amount);
    }

    private function receiveLikesCheck(User $user, int $amount): bool
    {
        $likesCount = $user->getLikesReceivedCount();
        return ($likesCount >= $amount);
    }

    private function postStoriesCheck(User $user, int $amount): bool
    {
        $startedStoriesCount = count($user->getStartedStories());
        return ($startedStoriesCount >= $amount);
    }

    private function postContributionsCheck(User $user, int $amount): bool
    {
        $contributionsCount = count($user->getContributions());
        return ($contributionsCount >= $amount);
    }

    private function verifyDailyLogin(User $user): bool
    {
        $lastLoginDate = $user->getLastLoginDate();
        $currentDate = new DateTime();

        // Check if the user has already logged in today
        if ($lastLoginDate !== null && $lastLoginDate->format('Y-m-d') === $currentDate->format('Y-m-d')) {
            return false; // User has already logged in today
        }

        $user->setLastLoginDate($currentDate);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true; // Daily login verified for the first time
    }

    public function calculateQuestProgress(Quest $quest, User $user): float
    {
        $questRequirement = $quest->getRequirement();
        $userRequirement = $user->getUserRequirement($questRequirement);

        if ($userRequirement === -1) {
            // Unsupported or unknown quest requirement
            return 0.0;
        }

        // Calculate the progress as a percentage
        $progress = ($userRequirement / $quest->getAmount()) * 100;

        // Ensure the progress is within the range of 0 to 100
        $progress = min(max($progress, 0), 100);

        return $progress;
    }
}