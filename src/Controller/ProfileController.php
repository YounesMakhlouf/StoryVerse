<?php

namespace App\Controller;

use App\Repository\TierRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/profile/{id}', name: 'app_profile')]
    public function showProfile(int $id, UserRepository $userRepository, TierRepository $tierRepository): Response
    {
        $user = $userRepository->find($id);
        // Check if the retrieved user exists
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }
        $contributedStories = $user->getContributedStories();
        $nextTier = $tierRepository->findNextTier($user->getXp());
        // Check if the current user is following the target user
        $isFollowing = false;
        $currentUser = $this->getUser();
        if ($currentUser && $currentUser->getId() !== $id) {
            $isFollowing = $currentUser->getFollowing()->contains($user);
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'is_following' => $isFollowing,
            'stories' => $contributedStories,
            'nextTier' => $nextTier
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/followers/{id}', name: 'app_followers')]
    public function showFollowers(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        $followers = $user->getFollower();
        return $this->render('profile/followers.html.twig', [
            'followers' => $followers
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/following/{id}', name: 'app_following')]
    public function showFollowing(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        $following = $user->getFollowing();
        return $this->render('profile/followers.html.twig', [
            'followers' => $following
        ]);
    }
}