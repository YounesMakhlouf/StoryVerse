<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompletedStoryController extends AbstractController
{
    #[Route('/completed/story/{id}  ', name: 'app_completed_story')]
    public function index(): Response
    {
        return $this->render('completed_story/index.html.twig', [
            'controller_name' => 'CompletedStoryController',
        ]);
    }
}
