<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TierRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     */
    #[Route('/profile/{id}', name: 'app_profile', methods: ['GET'])]
    public function showProfile(User $user, TierRepository $tierRepository): Response
    {
        $contributedStories = $user->getContributedStories();
        $nextTier = $tierRepository->findNextTier($user->getXp());

        // Check if the current user is following the target user
        $isFollowing = false;
        $currentUser = $this->getUser();
        if ($currentUser instanceof User && $currentUser !== $user) {
            $isFollowing = $currentUser->getFollowing()->contains($user);
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'is_following' => $isFollowing,
            'stories' => $contributedStories,
            'nextTier' => $nextTier,
        ]);
    }

    #[Route('/followers/{id}', name: 'app_followers', methods: ['GET'])]
    public function showFollowers(User $user): Response
    {
        $followers = $user->getFollower();

        return $this->render('profile/followers.html.twig', [
            'user' => $user,
            'followers' => $followers,
            'type' => 'followers',
        ]);
    }

    #[Route('/following/{id}', name: 'app_following', methods: ['GET'])]
    public function showFollowing(User $user): Response
    {
        $following = $user->getFollowing();

        return $this->render('profile/followers.html.twig', [
            'user' => $user,
            'followers' => $following,
            'type' => 'following',
        ]);
    }
}