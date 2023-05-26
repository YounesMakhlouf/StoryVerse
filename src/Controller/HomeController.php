<?php

namespace App\Controller;

use App\Repository\StoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(StoryRepository $storyRepository): Response
    {
        // If the user is already logged in, redirect him to his feed
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_browse_stories');
        }
        $trendingStories = $storyRepository->createOrderedByLikesQueryBuilder()->setMaxResults(3)->getQuery()
            ->getResult();
        $teamMembers = $this->getTeamMembers();
        return $this->render('/index.html.twig', [
            'teamMembers' => $teamMembers,
            'trendingStories' => $trendingStories,
        ]);
    }
     /**
     * Returns an array of team members with their details and social media profiles
     *
     * @return array
     */
    private function getTeamMembers(): array
    {
        return [
            [
                'firstName' => 'Younes',
                'lastName' => 'Makhlouf',
                'position' => 'Master of Memes',
                'social' => [
                    'facebook' => 'https://www.facebook.com/BabyFaker24/',
                    'instagram' => 'https://www.instagram.com/younes___makhlouf/',
                    'linkedin' => 'https://www.linkedin.com/in/younes-makhlouf-608321255/'
                ]
            ],
            [
                'firstName' => 'Salma',
                'lastName' => 'Bouabidi',
                'position' => 'Ambassador of Awesomeness',
                'social' => [
                    'facebook' => 'https://www.facebook.com/salma.bouabidi',
                    'instagram' => 'https://www.instagram.com/salmabouabidi_19/',
                    'linkedin' => 'https://www.linkedin.com/in/selma-bouabidi-938b08237/'
                ]
            ],
            [
                'firstName' => 'Wided',
                'lastName' => 'Oueslati',
                'position' => 'Captain of Comedy',
                'social' => [
                    'facebook' => 'https://www.facebook.com/profile.php?id=100069807314531',
                    'instagram' => 'https://www.instagram.com/wided_ouesletyy/',
                ]
            ],
            [
                'firstName' => 'Skander',
                'lastName' => 'Ben Achour',
                'position' => 'Head of Snack Acquisition',
                'social' => [
                    'facebook' => 'https://www.facebook.com/skander.benachour.1',
                    'instagram' => 'https://www.instagram.com/skander_ben_achour/',
                ]
            ],
            [
                'firstName' => 'Yasmine',
                'lastName' => 'Riahi',
                'position' => 'Vibe Manager',
                'social' => [
                    'facebook' => 'https://www.facebook.com/windy.jasmin.12072001',
                    'linkedin' => 'https://www.linkedin.com/in/yassmine-riahi-4685bb250/'
                ]
            ]
        ];
    }
}