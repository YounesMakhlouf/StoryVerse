<?php

namespace App\EventListener;

use App\Event\QuestActionEvent;
use App\Service\QuestCompletionService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class QuestActionListener implements EventSubscriberInterface
{
    private QuestCompletionService $questCompletionService;

    public function __construct(QuestCompletionService $questCompletionService)
    {
        $this->questCompletionService = $questCompletionService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            QuestActionEvent::QUEST_ACTION_EVENT => 'onQuestAction',
        ];
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function onQuestAction(QuestActionEvent $event): void
    {
        // Access the user and story associated with the action event
        $user = $event->getUser();
        // Perform quest completion logic based on the action
        $this->questCompletionService->checkQuestsForCompletion($user);
    }
}
