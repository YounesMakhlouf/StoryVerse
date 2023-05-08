<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoryCreateController extends AbstractController
{
    #[Route('/story/create', name: 'app_story_create')]
    public function index(): Response
    {
        return $this->render('story_create/index.html.twig', [
            'controller_name' => 'StoryCreateController',
        ]);
    }
}
