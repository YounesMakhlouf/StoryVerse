<?php

namespace App\Controller\Story;

use App\Entity\Comment;
use App\Entity\Contribution;
use App\Entity\Story;
use App\Form\CommentType;
use App\Form\ContributionType;
use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class StorypageController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly StoryRepository $storyRepository)
    {
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/storypage/{id}', name: 'app_storypage')]
    public function index(Request $request, int $id): Response
    {
        $story = $this->findStoryById($id);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $contribution = new Contribution();
        $contributionForm = $this->createForm(ContributionType::class, $contribution);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->addComment($comment, $story, $this->entityManager);

        }
        $contributionForm->handleRequest($request);

        if ($contributionForm->isSubmitted()) {
            return $this->addContribution($story, $contribution);
        }

        $hasContributed = $this->hasContributed($story);
        $hasLiked = $story->getLikes()->contains($this->getUser());

        return $this->render('storypage/story.html.twig', [
            'story' => $story,
            'hasLiked' => $hasLiked,
            'hasContributed' => $hasContributed,
            'form' => $form->createView(),
            'contributionForm' => $contributionForm->createView()
        ]);
    }

    private function findStoryById(int $id): Story
    {
        $story = $this->storyRepository->find($id);

        if (!$story instanceof Story) {
            throw $this->createNotFoundException('Story not found');
        }

        return $story;
    }

    public function addComment(Comment $comment, Story $story, EntityManagerInterface $entityManager): JsonResponse
    {
        $comment->setAuthor($this->getUser());
        $comment->setStory($story);
        $entityManager->persist($comment);
        $entityManager->flush();

        // Return a JSON response with the new comment data
        return $this->json([
            'content' => $comment->getContent(),
            'author' => $comment->getAuthor()->getUsername(),
            'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i:s'),
            'count' => $story->getComments()->count(),
            'avatar' => $comment->getAuthor()->getAvatar()
        ]);
    }

    public function addContribution(Story $story, Contribution $contribution): JsonResponse
    {
        $contribution->setStory($story);
        $contribution->setAuthor($this->getUser());
        $contribution->setPosition($story->getContributions()->count() + 1);
        $this->entityManager->persist($contribution);
        $this->entityManager->flush();
        return $this->json([
            'content' => $contribution->getContent(),
            'id' => $contribution->getAuthor()->getId(),
        ]);
    }

    private function hasContributed(Story $story): bool
    {
        $user = $this->getUser();
        $contributions = $this->entityManager->getRepository(Contribution::class)->findBy([
            'author' => $user,
            'story' => $story,
        ]);

        return count($contributions) > 0;
    }

    #[Route('/storypage/like/{id}', name: 'app_like')]
    public function addLike(int $id): JsonResponse
    {
        $story = $this->findStoryById($id);
        $user = $this->getUser();

        if ($story->getLikes()->contains($user)) {
            $story->removeLike($user);
        } else {
            $story->addLike($user);
        }

        $this->entityManager->persist($story);
        $this->entityManager->flush();

        return $this->json([
            'count' => $story->getlikes()->count()
        ]);
    }
}