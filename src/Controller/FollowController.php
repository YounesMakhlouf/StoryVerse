<?php
    namespace App\Controller;

    use App\Entity\Follow;
    use App\Entity\User;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\ORM\EntityManagerInterface;

    class FollowController extends AbstractController
    {
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
    $this->entityManager = $entityManager;
    }

    #[Route('/profile/{id}/follow', name: 'follow_user')]
    public function followUser(Request $request, User $user): Response
    {
    if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
    $this->addFlash('warning', 'You must be logged in to follow users');
    } else {
    // Check if the current user is already following the target user
    $existingFollow = $this->entityManager->getRepository(Follow::class)->findOneBy(['user' => $this->getUser(), 'following' => $user]);

    if ($existingFollow) {
    $this->addFlash('warning', 'You are already following this user');
    } else {
    // Create a new Follow entity to represent the relationship between the users
    $follow = new Follow();
    $follow->setUser($this->getUser());
    $follow->setFollowing($user);

    $this->entityManager->persist($follow);
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
                $isFollowing = $this->entityManager->getRepository(Follow::class)->findOneBy(['user' => $currentUser, 'following' => $user]) !== null;
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
                // Check if the current user is already following the target user
                $existingFollow = $this->entityManager->getRepository(Follow::class)->findOneBy(['user' => $this->getUser(), 'following' => $user]);

                if ($existingFollow) {
                    $this->entityManager->remove($existingFollow);
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
