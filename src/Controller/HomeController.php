<?php

namespace App\Controller;

use App\Repository\StoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    private const MAX_TRENDING_STORIES = 3;
    private CacheInterface $cache;
    private string $teamMembersFile;

    public function __construct(CacheInterface $cache, string $teamMembersFile)
    {
        $this->cache = $cache;
        $this->teamMembersFile = $teamMembersFile;
    }

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

        $teamMembers = $this->getTeamMembers();

        return $this->render('index.html.twig', [
            'teamMembers' => $teamMembers,
            'trendingStories' => $trendingStories,
        ]);
    }

    private function getTeamMembers(): array
    {
        // Use cache to store team members data
        return $this->cache->get('team_members', function (ItemInterface $item) {
            $item->expiresAfter(3600); // Cache for 1 hour

            // Read from the JSON file
            $data = json_decode(file_get_contents($this->teamMembersFile), true);

            // Handle parsing errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Failed to parse team members data: ' . json_last_error_msg());
            }

            return $data;
        });
    }
}