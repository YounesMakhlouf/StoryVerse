<?php

namespace App\Controller;

use App\Event\QuestActionEvent;
use App\Repository\QuestRepository;
use App\Repository\UserRepository;
use App\Service\QuestCompletionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestController extends AbstractController
{
    public function __construct(private EventDispatcherInterface $eventDispatcher,
                                private QuestCompletionService   $questCompletionService)
    {
    }

    #[Route('/quest', name: 'app_quest')]
    public function index(QuestRepository $questRepository, UserRepository $userRepository): Response
    {
        $quests = $questRepository->findAll();
        $user = $this->getUser();

        $event = new QuestActionEvent($user);
        $this->eventDispatcher->dispatch($event, QuestActionEvent::QUEST_ACTION_EVENT);

        return $this->render('quest/index.html.twig', [
            'quests' => $quests,
            'questCompletionService' => $this->questCompletionService,
            'userRepository' => $userRepository,
        ]);
    }
}
