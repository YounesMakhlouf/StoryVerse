<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/notification/create', name: 'app_notification_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        // Retrieve the user, content, and sender from the request
        $receiver = $this->getUserById($request->request->get('receiver_id'));
        $content = $request->request->get('content');
        $sender = $this->getUserById($request->request->get('sender_id'));

        if (!$receiver || !$sender) {
            return $this->json(['success' => false, 'error' => 'Invalid receiver or sender'], 400);
        }
        // Create a new Notification instance
        $notification = new Notification($receiver, $content, $sender);
        // Save the new Notification instance to the database
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
        // Return a JSON response indicating success
        return $this->json(['success' => true]);
    }

    #[Route('/notification/user', name: 'app_notification_user', methods: ['GET'])]
    public function getUserNotifications(): JsonResponse
    {
        $user = $this->getUser();
        $notifications = $this->entityManager->getRepository(Notification::class)->findBy([
            'receiver' => $user,
        ]);
        // Map the notifications to an array of data
        $notificationData = array_map([$this, 'mapNotificationToData'], $notifications);
        return new JsonResponse($notificationData);
    }

    private function getUserById(int $id): ?User
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    private function mapNotificationToData(Notification $notification): array
    {
        return [
            'id' => $notification->getId(),
            'content' => $notification->getContent(),
            'sender' => [
                'id' => $notification->getSender()->getId(),
                'name' => $notification->getSender()->getFirstName(),
            ],
        ];
    }
}