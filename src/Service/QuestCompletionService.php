<?php

namespace App\Service;

use App\Entity\Quest;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class QuestCompletionService
{
    private ObjectRepository|EntityRepository $questRepository;

    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly RequestStack           $requestStack,
                                private readonly UrlGeneratorInterface  $urlGenerator)
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

        if (($requirement === 'daily_login') && ($this->verifyDailyLogin($user))) {
            $this->markQuestAsCompleted($user, $quest);
            return;
        }
        $userRequirement = $user->getUserRequirement($requirement);

        if ($userRequirement === -1) { // Unsupported or unknown quest requirement
            return;
        }

        if ($userRequirement >= $amount) {
            $this->markQuestAsCompleted($user, $quest);
        }
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

    private function markQuestAsCompleted(User $user, Quest $quest): void
    {
        $user->addCompletedQuest($quest);
        $user->addXp($quest->getPoints());
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        // Show SweetAlert2 notification
        $questName = $quest->getName();
        $notificationMessage = "Congratulations brave adventurer! You have completed the quest: $questName";
        $notificationUrl = $this->urlGenerator->generate('app_quest');

        $script = <<<SCRIPT
        <script>
            Swal.fire({
                title: 'Quest Completed!',
                text: '$notificationMessage',
                icon: 'success',
                showCancelButton: false,
                confirmButtonText: 'OK',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '$notificationUrl';
                }
            });
        </script>
    SCRIPT;

        $request = $this->requestStack->getCurrentRequest();
        $request->getSession()->getFlashBag()->add('sweetalert2', $script);
    }

    public function calculateQuestProgress(Quest $quest, User $user): float
    {
        $questRequirement = $quest->getRequirement();
        $userRequirement = $user->getUserRequirement($questRequirement);

        if ($userRequirement === -1) { // Unsupported or unknown quest requirement
            return 0.0;
        }

        // Calculate the progress as a percentage
        $progress = ($userRequirement / $quest->getAmount()) * 100;

        // Ensure the progress is within the range of 0 to 100
        return min(max($progress, 0), 100);
    }
}