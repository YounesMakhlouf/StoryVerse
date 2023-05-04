<?php

namespace App\Controller;

use App\Entity\Story;
use App\Repository\GenreRepository;
use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $story->setLanguage("english");
        $story->setLikes(rand(0, 100));
        $story->setStatus("pending");
        $entityManager->persist($story);
        $entityManager->flush();

        return new response (sprintf(
            "%s", $story->getTitle()
        ));
    }

    #[Route('/story/browse/{genre}', name: 'app_browse_stories')]
    public function browse(StoryRepository $storyRepository, Request $request, GenreRepository $genreRepository, string $genre = null): Response
    {
        $queryBuilder = $storyRepository->createOrderedByLikesQueryBuilder($genre);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            9
        );
        $genres = $genreRepository->findAll();

        return $this->render('story/browse.html.twig', [
            'genre' => $genre,
            'pager' => $pagerfanta,
            'genres' => $genres
        ]);
    }

    #[Route('/story/{slug}', name: 'app_story_id')]
    public function show(Story $story): Response
    {
        return $this->render('story/index.html.twig', [
            'story' => $story,
            'slug' => $story->getSlug(),
        ]);
    }
}
