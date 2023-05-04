<?php

namespace App\Controller;

use App\Entity\Follow;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
   /* #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController'
        ]);
    }*/

    
        #[Route('/profile/{id}', name: 'app_profile')]
        public function showProfile($id, EntityManagerInterface $entityManager): Response
        {
            /** @var UserRepository $userRepository */
            $userRepository = $entityManager->getRepository(User::class);
    
            // Récupérer l'utilisateur avec l'ID donné
            $user = $userRepository->find($id);

            // Check if the current user is following the target user
            $isFollowing = false;
            if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
                $currentUser = $this->getUser();
                $isFollowing = $entityManager->getRepository(Follow::class)->findOneBy(['user' => $currentUser, 'following' => $user]) !== null;
            }

            return $this->render('profile/index.html.twig', [
                'user' => $user,
                'is_following' => $isFollowing,
                'phoneNumber' => '+216 55 216 719',
                'email' => 'storyverse19@gmail.com'
            ]);

        }
    }
    

