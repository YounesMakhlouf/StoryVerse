<?php

namespace App\Controller;

use App\Repository\StoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RechercheController extends AbstractController
{
    public function __construct(private readonly UserRepository $userRepository, private readonly StoryRepository $storyRepository)
    {
    }

    #[Route('/search', name: 'app_search')]
    public function search(Request $request): Response
    {
        $searchQuery = $request->query->get('searchQuery');

        $users = $this->userRepository->searchByUsername($searchQuery);
        $stories = $this->storyRepository->searchByTitle($searchQuery);

        return $this->render('recherche/recherche.html.twig', [
            'users' => $users,
            'stories' => $stories,
            'searchQuery' => $searchQuery,
        ]);
    }
}
