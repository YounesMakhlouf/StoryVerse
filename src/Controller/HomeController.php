<?php

namespace App\Controller;

use App\Repository\StoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private const MAX_TRENDING_STORIES = 3;

    #[Route('/', name: 'app_home')]
    public function index(StoryRepository $storyRepository): Response
    {
        // If the user is already logged in, redirect him to his feed
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_browse_stories');
        }

        $trendingStories = $storyRepository->createOrderedByLikesQueryBuilder()
            ->setMaxResults(self::MAX_TRENDING_STORIES)
            ->getQuery()
            ->getResult();

        return $this->render('/index.html.twig', [
            'teamMembers' => $this->getTeamMembers(),
            'trendingStories' => $trendingStories,
        ]);
    }

    private function getTeamMembers(): array
    {
        $dir = dirname(__DIR__, 2);
        return json_decode(file_get_contents("{$dir}/data/team.json"), true);
    }
}