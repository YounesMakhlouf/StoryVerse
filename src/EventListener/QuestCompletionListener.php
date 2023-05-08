<?php

namespace App\EventListener;

use App\Event\QuestCompletionEvent;
use App\Service\QuestCompletionService;

class QuestCompletionListener
{
//    private QuestCompletionService $questCompletionService;

//    public function __construct(QuestCompletionService $questCompletionService)
//    {
//        $this->questCompletionService = $questCompletionService;
//    }

    public function onQuestCompletion(QuestCompletionEvent $event): void
    {
        $user = $event->getUser();
//        $this->questCompletionService->checkQuestsForCompletion($user);
       dd($user);
    }
}
