<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationTrierController extends AbstractController
{
    #[Route('/notification/trier', name: 'app_notification_trier')]
    public function index(): Response
    {
        return $this->render('notification_trier/index.html.twig', [
            'controller_name' => 'NotificationTrierController',
        ]);
    }
}
