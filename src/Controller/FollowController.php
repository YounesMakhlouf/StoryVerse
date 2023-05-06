<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FollowController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/profile/{id}/follow', name: 'follow_user')]
    public function followUser(User $user): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash('warning', 'You must be logged in to follow users');
        } else {
            $currentUser = $this->getUser();
            // Check if the current user is already following the target user
            if ($currentUser->getFollowing()->contains($user)) {
                $this->addFlash('warning', 'You are already following this user');
            } else {
                // Add the target user to the current user's following collection
                $currentUser->addFollowing($user);
                $this->entityManager->flush();

                $this->addFlash('success', 'You are now following this user');
            }
        }
        // Redirect back to the target user's profile page
        return $this->redirectToRoute('app_profile', ['id' => $user->getId()]);
    }

    #[Route('/profile/{id}/is_following', name: 'is_following')]
    public function isFollowing(User $user): JsonResponse
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $currentUser = $this->getUser();
            $isFollowing = $currentUser->getFollowing()->contains($user);
        } else {
            $isFollowing = false;
        }

        return $this->json(['isFollowing' => $isFollowing]);
    }

    #[Route('/profile/{id}/unfollow', name: 'unfollow_user', methods: ['POST'])]
    public function unfollowUser(User $user): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash('warning', 'You must be logged in to unfollow users');
        } else {
            $currentUser = $this->getUser();

            // Check if the current user is already following the target user
            if ($currentUser->getFollowing()->contains($user)) {
                // Remove the target user from the current user's following collection
                $currentUser->removeFollowing($user);
                $this->entityManager->flush();

                $this->addFlash('success', 'You have unfollowed this user');
            } else {
                $this->addFlash('warning', 'You are not following this user');
            }
        }

        // Redirect back to the target user's profile page
        return $this->redirectToRoute('app_profile', ['id' => $user->getId()]);
    }
}
