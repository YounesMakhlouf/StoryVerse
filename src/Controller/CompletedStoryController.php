<?php

namespace App\Controller;

use App\Repository\StoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CompletedStoryController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/completed/story/{id}  ', name: 'app_completed_story')]
    public function index($id,StoryRepository $storyRepository): Response
    {
        $story=$storyRepository->find($id);
        $title=$story->getTitle();
        $contributions=$story->getContribution();
        $genre=$story->getGenre();
        return $this->render('completed_story/index.html.twig', [
            'controller_name' => 'CompletedStoryController',
            'contributions'=>$contributions,
            'title'=>$title,
            'genre'=>$genre

        ]);
    }
}
