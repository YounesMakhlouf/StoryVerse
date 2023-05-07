<?php

namespace App\Controller;

use App\Repository\QuestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestController extends AbstractController
{
    #[Route('/quest', name: 'app_quest')]
    public function index(QuestRepository $questRepository): Response
    {
        $quests = $questRepository->findAll();
        return $this->render('quest/index.html.twig', [
            'quests' => $quests,
        ]);
    }
}
