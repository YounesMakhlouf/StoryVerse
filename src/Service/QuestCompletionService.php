<?php

namespace App\Service;

use App\Entity\Quest;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ObjectRepository;

class QuestCompletionService
{
    private ObjectRepository|EntityRepository $questRepository;

    /**
     * @throws NotSupported
     */
    public function __construct(private readonly EntityManager $entityManager)
    {
        $this->questRepository = $entityManager->getRepository(Quest::class);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function checkQuestsForCompletion(User $user): void
    {
        $quests = $this->questRepository->findAll();

        foreach ($quests as $quest) {
            if (!$user->getCompletedQuests()->contains($quest)) {
                $this->completeQuest($user, $quest);
            }
        }
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */

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

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    private function markQuestAsCompleted(User $user, Quest $quest): void
    {
        $user->addCompletedQuest($quest);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function receiveCommentsCheck(User $user, int $amount): bool
    {
        $contributedStories = $user->getContributedStories();
        $commentCount = 0;
        foreach ($contributedStories as $story) {
            $comments = $story->getComments();
            $commentCount += count($comments);
        }
        return ($commentCount >= $amount);
    }

    private function postLikesCheck(User $user, int $amount): bool
    {
        $likesCount = count($user->getLikedStories());
        return ($likesCount >= $amount);
    }

    private function receiveLikesCheck(User $user, int $amount): bool
    {
        $contributedStories = $user->getContributedStories();
        $likesCount = 0;
        foreach ($contributedStories as $story) {
            $likes = $story->getLikes();
            $likesCount += count($likes);
        }
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

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
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
}