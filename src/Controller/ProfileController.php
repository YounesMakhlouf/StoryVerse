<?php

namespace App\Controller;

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
            'controller_name' => 'ProfileController',
            'phoneNumber' => '+216 55 216 719',
            'email' => 'storyverse19@gmail.com'
        ]);
    }*/

    
        #[Route('/profile/{id}', name: 'app_profile')]
        public function showProfile($id, EntityManagerInterface $entityManager): Response
        {
            /** @var UserRepository $userRepository */
            $userRepository = $entityManager->getRepository(User::class);
    
            // Récupérer l'utilisateur avec l'ID donné
            $user = $userRepository->find($id);
    
            return $this->render('profile/index.html.twig', [
                'user' => $user,
                'phoneNumber' => '+216 55 216 719',
                'email' => 'storyverse19@gmail.com' 
            ]);
        }
    }
    

