<?php

namespace App\EventListener;

use App\Event\QuestActionEvent;
use App\Service\QuestCompletionService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class QuestActionListener implements EventSubscriberInterface
{
    public function __construct(private readonly QuestCompletionService $questCompletionService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [QuestActionEvent::QUEST_ACTION_EVENT => 'onQuestAction',];
    }

    public function onQuestAction(QuestActionEvent $event): void
    {
        $user = $event->getUser();
        $this->questCompletionService->checkQuestsForCompletion($user);
    }
}
