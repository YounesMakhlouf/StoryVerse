<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class userController extends AbstractController
{
    #[Route("/user/{id}", methods: ['GET'], name: 'app_user')]
    public function User($id)
    {
        return new Response('' . $id);
    }
}
