<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    /* #[Route('/profile', name: 'app_profile')]
     public function index(): Response
     {
         return $this->render('profile/index.html.twig', [
             'controller_name' => 'ProfileController'
         ]);
     }*/

    #[IsGranted('ROLE_USER')]
    #[Route('/profile/{id}', name: 'app_profile')]
    public function showProfile($id, EntityManagerInterface $entityManager): Response
    {
        /** @var UserRepository $userRepository */
        $userRepository = $entityManager->getRepository(User::class);

        // Get the user with the given ID
        $user = $userRepository->find($id);
        $contributedStories = $user->getContributedStories();

        // Check if the current user is following the target user
        $isFollowing = false;
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            /** @var User $currentUser */
            $currentUser = $this->getUser();
            $isFollowing = $currentUser->getFollowing()->contains($user);
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'is_following' => $isFollowing,
            'stories' => $contributedStories
        ]);
    }
}