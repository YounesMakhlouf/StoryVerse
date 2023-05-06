<?php
// src/Controller/NotificationController.php

namespace App\Controller;
use App\Service\NotificationService;
use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    
    #[Route("/send-notification", name :"send_notification")]
    
    public function sendNotification()
    {
    }
}
