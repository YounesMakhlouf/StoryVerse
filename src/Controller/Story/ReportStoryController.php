<?php

namespace App\Controller\Story;

use App\Entity\Story;
use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ReportStoryController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/report/story/{id}', name: 'app_report_story')]
    public function index($id, StoryRepository $storyRepository, EntityManagerInterface $entityManager): Response
    {
        $story = $storyRepository->find($id);

        if (!$story instanceof Story) {
            throw $this->createNotFoundException('Story not found');
        }

        $story->setIsReported(true);
        $entityManager->persist($story);
        $entityManager->flush();
        return $this->json(['message' => 'Story reported successfully']);
    }
}