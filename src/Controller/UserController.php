<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route("/user/{id}", name: 'app_user', methods: ['GET'])]
    public function User($id): Response
    {
        return new Response('' . $id);
    }
}
