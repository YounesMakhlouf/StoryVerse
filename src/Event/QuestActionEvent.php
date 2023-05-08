<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class QuestActionEvent extends Event
{
    private User $user;
    const QUEST_ACTION_EVENT = 'action.quest';

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
