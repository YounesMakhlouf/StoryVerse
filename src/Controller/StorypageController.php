<?php

namespace App\Controller;

use App\Repository\StoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class StorypageController extends AbstractController
{   #[IsGranted('ROLE_USER')]
    #[Route('/storypage/{id}', name: 'app_storypage')]
    public function index($id,StoryRepository $storyRepository): Response
    {
        $story=$storyRepository->find($id);
        $title=$story->getTitle();
        $contributions=$story->getContributions();
        $genre=$story->getGenre();
        $comments=$story->getComments();
        return $this->render('storypage/index.html.twig', [
            'controller_name' => 'CompletedStoryController',
            'contributions'=>$contributions,
            'title'=>$title,
            'genre'=>$genre,
            'comments'=>$comments

        ]);
    }
    
}
