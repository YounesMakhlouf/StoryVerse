<?php

namespace App\Controller\Story;

use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportStoryController extends AbstractController
{
    #[Route('/report/story/{id}', name: 'app_report_story')]
    public function index($id , StoryRepository $storyRepository,EntityManagerInterface $entityManager): Response
    {
        $story=$storyRepository->find($id);
        $story->setIsReported(true);
        $entityManager->persist($story);
        $entityManager->flush();
        return $this->json([

            'message'=>'Story reported successfully'
        ]);



    }
}
