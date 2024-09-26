<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FollowController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/profile/{id}/follow', name: 'follow_user', methods: ['POST'])]
    public function followUser(User $user): Response
    {
        $currentUser = $this->getUser();
        if ($currentUser->getFollowing()->contains($user)) {
            $this->addFlash('warning', 'You are already following this user');
        } else {
            // Add the target user to the current user's following collection
            $currentUser->addFollowing($user);
            $this->entityManager->flush();
        }
        $this->addFlash('success', 'You are now following ' . $user->getUsername());
        // Redirect back to the target user's profile page
        return $this->redirectToRoute('app_profile', ['id' => $user->getId()]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/profile/{id}/is_following', name: 'is_following')]
    public function isFollowing(User $user): JsonResponse
    {
        $isFollowing = $this->isGranted('IS_AUTHENTICATED_FULLY') ? $this->getUser()->getFollowing()->contains($user) : false;

        return $this->json(['isFollowing' => $isFollowing]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/profile/{id}/unfollow', name: 'unfollow_user', methods: ['POST'])]
    public function unfollowUser(User $user): Response
    {
        $currentUser = $this->getUser();
        if ($currentUser->getFollowing()->contains($user)) {
            // Remove the target user from the current user's following collection
            $currentUser->removeFollowing($user);
            $this->entityManager->flush();
        } else {
            $this->addFlash('warning', 'You are not following this user');
        }
        $this->addFlash('success', 'You have unfollowed ' . $user->getUsername());
        // Redirect back to the target user's profile page
        return $this->redirectToRoute('app_profile', ['id' => $user->getId()]);
    }
}