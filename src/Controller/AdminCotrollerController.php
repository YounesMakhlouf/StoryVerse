<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCotrollerController extends AbstractController
{
    #[Route('/admin', name: 'app_add_admin')]
    public function index(): Response
    {return $this->render(':profile:index.html.twig');
    }
}
