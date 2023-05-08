<?php

namespace App\Controller;

use App\Entity\Story;
use App\Event\QuestActionEvent;
use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoryController extends AbstractController
{

    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    #[Route('/story/new', name: 'app_add_story')]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $story = new Story();
        $story->setTitle("yalla habibi");
        $story->setLanguage("english");
        $story->setStatus("pending");
        $entityManager->persist($story);
        $entityManager->flush();

        return new response (sprintf(
            "%s", $story->getTitle()
        ));
    }

    #[Route('/story/browse/{genre}', name: 'app_browse_stories')]
    public function browse(StoryRepository $storyRepository, Request $request, string $genre = null): Response
    {
        $user = $this->getUser();

        $event = new QuestActionEvent($user);
        $this->eventDispatcher->dispatch($event, QuestActionEvent::QUEST_ACTION_EVENT);
        $maxPerPage = $this->getParameter('max_stories_per_page');
        $genres = $this->getParameter('story_genres');
        $queryBuilder = $storyRepository->createOrderedByLikesQueryBuilder($genre);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            $maxPerPage
        );

        return $this->render('story/browse.html.twig', [
            'genre' => $genre,
            'pager' => $pagerfanta,
            'genres' => $genres,
        ]);
    }
}
