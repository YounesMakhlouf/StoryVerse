<?php

namespace App\Controller;

use App\Entity\Story;
use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoryController extends AbstractController
{
    #[Route('/story', name: 'app_story')]
    public function index(): Response
    {
        return $this->render('story/index.html.twig', [
            'controller_name' => 'StoryController'
        ]);
    }

    #[Route('/story/new', name: 'app_add_story')]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $story = new Story();
        $story->setTitle("yalla habibi");
        $story->setDescription("yalla habibiyalla habibiyalla habibiyalla habibiyalla habibiyalla habibiyalla habibiyalla habibi");
        $story->setLanguage("english");
        $story->setLikes(rand(0, 100));
        $story->setStatus("pending");
        $entityManager->persist($story);
        $entityManager->flush();

        return new response (sprintf(
            "%s%s", $story->getTitle(), $story->getDescription()
        ));
    }

    #[Route('/story/browse/{slug}', name: 'app_browse_story')]
    public function browse(StoryRepository $storyRepository, string $slug = null): Response
    {
        $genre = $slug;
        $stories = $storyRepository->findAllOrderedByLikes();

        return $this->render('story/browse.html.twig', [
            'genre' => $genre,
            'trendingStories' => $stories,
        ]);
    }
}
