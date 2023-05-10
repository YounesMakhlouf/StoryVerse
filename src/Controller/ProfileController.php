<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TierRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class ProfileController extends AbstractController
{

    /**
     * @throws NonUniqueResultException
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/profile/{id}', name: 'app_profile')]
    public function showProfile($id, EntityManagerInterface $entityManager, TierRepository $tierRepository): Response
    {
        // Check if the current user is viewing their own profile
        if (($this->getUser())->getId() == $id) {
            return $this->redirectToRoute('app_myprofile');
        } else {

            /** @var UserRepository $userRepository */
            $userRepository = $entityManager->getRepository(User::class);

            // Get the user with the given ID
            $user = $userRepository->find($id);
            $contributedStories = $user->getContributedStories();

            // Check if the current user is following the target user
            /** @var User $currentUser */
            $currentUser = $this->getUser();
            $isFollowing = $currentUser->getFollowing()->contains($user);



            $nextTier = $tierRepository->findNextTier($user->getXp());
            return $this->render('profile/index.html.twig', [
                'user' => $user,
                'is_following' => $isFollowing,
                'stories' => $contributedStories,
                'nextTier' => $nextTier
            ]);
        }
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/followers/{id}', name: 'app_followers')]
    public function showFollowers($id,UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        $followers=$user->getFollower();
        return $this->render('profile/followers.html.twig',[
            'followers'=>$followers
        ]);

    }
    #[IsGranted('ROLE_USER')]
    #[Route('/following/{id}', name: 'app_following')]
    public function showFollowing($id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        $following=$user->getFollowing();
        return $this->render('profile/followers.html.twig',[
            'followers'=>$following
        ]);

    }

    }