<?php

namespace App\Controller;

use App\Event\QuestActionEvent;
use App\Repository\StoryRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class StoryController extends AbstractController
{
    private $storyGenres;
    private $maxPerPage;

    public function __construct(ParameterBagInterface                     $params,
                                private readonly EventDispatcherInterface $eventDispatcher,
                                private readonly StoryRepository          $storyRepository)
    {
        $this->storyGenres = $params->get('story_genres');
        $this->maxPerPage = $params->get('max_stories_per_page');
    }

    #[Route('/story/browse/{genre}', name: 'app_browse_stories')]
    public function browseStoriesByGenre(Request $request, ?string $genre = null): Response
    {
        $violations = $this->validateGenre($genre);
        if (count($violations) > 0) {
            throw new BadRequestHttpException('Invalid genre');
        }
        $event = new QuestActionEvent($this->getUser());
        $this->eventDispatcher->dispatch($event, QuestActionEvent::QUEST_ACTION_EVENT);

        $queryBuilder = $this->storyRepository->createOrderedByLikesQueryBuilder($genre);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->getInt('page', 1),
            $this->maxPerPage
        );

        return $this->render('story/browse.html.twig', [
            'genre' => $genre,
            'pager' => $pagerfanta,
            'genres' => $this->storyGenres,
        ]);
    }

    private function validateGenre(?string $genre): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();
        return $validator->validate($genre, [
            new Constraints\Choice(['choices' => array_keys($this->storyGenres)]),
        ]);
    }
}