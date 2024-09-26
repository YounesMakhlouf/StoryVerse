<?php

namespace App\Service;

use App\Entity\Quest;
use App\Entity\User;
use App\Event\QuestCompletedEvent;
use App\Repository\QuestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class QuestCompletionService
{
    private const UNSUPPORTED_REQUIREMENT = -1;

    public function __construct(private readonly TierService $tierService, private readonly RequestStack $requestStack, private readonly EntityManagerInterface $entityManager, private readonly QuestRepository $questRepository, private readonly UrlGeneratorInterface $urlGenerator)
    {}

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
        $requirement = $quest->getRequirement();
        $questAmount = $quest->getAmount();

        $userRequirement = $user->getUserRequirement($requirement);

        if ($userRequirement === self::UNSUPPORTED_REQUIREMENT) {
            return;
        }

        if ($userRequirement >= $questAmount) {
            $this->markQuestAsCompleted($user, $quest);
        }
    }

    public function markQuestAsCompleted(User $user, Quest $quest): void
    {
        $user->addCompletedQuest($quest);
        $user->addXp($quest->getPoints());
        $this->showQuestCompletedNotification($quest);

        $this->tierService->updateTierForUser($user);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function showQuestCompletedNotification(Quest $quest): void
    {
        $questName = $quest->getName();
        $message = "Congratulations brave adventurer! You have completed the quest: $questName";
        $url = $this->urlGenerator->generate('app_quest');
        $this->showNotification($message, $url);
    }

    /** Displays a notification using SweetAlert2 library
     * @param string $message The message to be displayed
     * @param string $url The destination URL to redirect to when user clicks OK button
     */
    private function showNotification(string $message, string $url): void
    {
        $script = <<<SCRIPT
            <script>
                Swal.fire({
                    title: 'Quest Completed!',
                    text: '$message',
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                }).then((result) => {
                            // Redirect to specified URL when user clicks OK button
                    if (result.isConfirmed) {
                        window.location.href = '$url';
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

        if ($questRequirement === 'create_account' || !$quest->getAmount()) {
            return 100;
        }

        $userRequirement = $user->getUserRequirement($questRequirement);

        if ($userRequirement === self::UNSUPPORTED_REQUIREMENT) {
            return 0.0;
        }

        // Calculate the progress as a percentage
        $progress = ($userRequirement / $quest->getAmount()) * 100;

        // Ensure the progress is within the range of 0 to 100
        return min(max($progress, 0.0), 100.0);
    }
}