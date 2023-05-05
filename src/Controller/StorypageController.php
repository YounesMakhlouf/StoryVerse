<?php

namespace App\Controller;

use App\Entity\Story;
use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StorypageController extends AbstractController
{
    #[Route('/storypage/{storyID}', name: 'app_storypage')]
    public function index(int $storyID, EntityManagerInterface $entityManager): Response
    {
        $story = $entityManager ->getRepository(Story::class)->find($storyID);
       
        return $this->render('storypage/index.html.twig', [
            'story' => $story
        ]);
    }
}
