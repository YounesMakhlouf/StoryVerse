<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Psr\Log\LoggerInterface;


class NotificationController extends AbstractController
{

    #[Route('/notification/create', name: 'app_notification_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Retrieve the user, content, and sender from the request
        var_dump("skander");
       
        
        $receiver = $entityManager->getRepository(User::class)->find($request->request->get('receiver_id'));
        $content = $request->request->get('content');
       
        $sender = $entityManager->getRepository(User::class)->find($request->request->get('sender_id'));

       
    
        // Create a new Notification instance
        $notification = new Notification($receiver, $content, $sender);
    var_dump("skander");
        // Save the new Notification instance to the database
        
        $entityManager->persist($notification);
        $entityManager->flush();
    
        // Return a JSON response indicating success
        return $this->json(['success' => true]);
    }
}
