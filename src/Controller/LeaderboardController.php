<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeaderboardController extends AbstractController
{
    #[Route('/leaderboard', name: 'app_leaderboard')]
    public function leaderboard(UserRepository $userRepository): Response
    {
        $users = $userRepository->findBy([], ['xp' => 'DESC']);

        return $this->render('leaderboard/index.html.twig', [
            'users' => $users,
        ]);
    }
}