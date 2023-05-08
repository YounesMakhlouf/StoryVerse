<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/notification/user/{userId}', name: 'app_notification_user', methods: ['GET'])]
    public function getUserNotifications(EntityManagerInterface $entityManager, int $userId): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($userId);
    
        if (!$user) {
            // Handle case where user does not exist
            throw $this->createNotFoundException('User not found');
        }
    
        $notifications = $entityManager->getRepository(Notification::class)->findBy([
            'receiver' => $user,
        ]);
    
        // Map the notifications to an array of data
        $notificationData = array_map(function ($notification) {
            return [
                'id' => $notification->getId(),
                'content' => $notification->getContent(),
                'sender' => [
                    'id' => $notification->getSender()->getId(),
                    'name' => $notification->getSender()->getfirstName(),
                ],
            ];
        }, $notifications);
    
        // Return the notification data as JSON in a JsonResponse object
        return new JsonResponse($notificationData);
    }
    
}
