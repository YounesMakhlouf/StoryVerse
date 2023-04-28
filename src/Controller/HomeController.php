<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $trendingStories = $this->getTrendingStories();
        $teamMembers = $this->getTeamMembers();

        return $this->render('/index.html.twig', [
            'controller_name' => 'HomeController',
            'teamMembers' => $teamMembers,
            'trendingStories' => $trendingStories,
        ]);
    }

    private function getTrendingStories(): array
    {
        return [
            [
                "title" => "My Awkward Encounter with a Celebrity",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque in eros ac ligula auctor fermentum eu sit amet leo. Praesent a accumsan nisi, vel ultricies nulla.",
                "duration" => "3 mins",
                "category" => "Comedy",
                "image" => "build/images/trending1.webp",
                "link" => "#"
            ],
            [
                "title" => "The Demon in the Mirror",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque in eros ac ligula auctor fermentum eu sit amet leo. Praesent a accumsan nisi, vel ultricies nulla.",
                "duration" => "10 mins",
                "category" => "Horror",
                "image" => "build/images/trending2.webp",
                "link" => "#"
            ],
            [
                "title" => "The Time Traveler's Dilemma",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque in eros ac ligula auctor fermentum eu sit amet leo. Praesent a accumsan nisi, vel ultricies nulla.",
                "duration" => "5 mins",
                "category" => "Adventure",
                "image" => "build/images/trending3.webp",
                "link" => "#"
            ]
        ];
    }

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
                    'linkedin' => ''
                ]
            ],
            [
                'firstName' => 'Skander',
                'lastName' => 'Ben Achour',
                'position' => 'Head of Snack Acquisition',
                'social' => [
                    'facebook' => 'https://www.facebook.com/skander.benachour.1',
                    'instagram' => 'https://www.instagram.com/skander_ben_achour/',
                    'linkedin' => ''
                ]
            ],
            [
                'firstName' => 'Yasmine',
                'lastName' => 'Riahi',
                'position' => 'Vibe Manager',
                'social' => [
                    'facebook' => 'https://www.facebook.com/windy.jasmin.12072001',
                    'instagram' => '',
                    'linkedin' => 'https://www.linkedin.com/in/yassmine-riahi-4685bb250/'
                ]
            ]
        ];
    }
}