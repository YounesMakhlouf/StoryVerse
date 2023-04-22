<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(): Response
    {
        $teamMembers = [
            [
                'name' => 'Younes Makhlouf',
                'position' => 'Master of Memes',
                'image' => 'assets/img/team/younes.webp',
                'social' => [
                    'facebook' => 'https://www.facebook.com/BabyFaker24/',
                    'instagram' => 'https://www.instagram.com/younes___makhlouf/',
                    'linkedin' => 'https://www.linkedin.com/in/younes-makhlouf-608321255/'
                ]
            ],
            [
                'name' => 'Salma Bouabidi',
                'position' => 'Ambassador of Awesomeness',
                'image' => 'assets/img/team/salma.webp',
                'social' => [
                    'facebook' => 'https://www.facebook.com/salma.bouabidi',
                    'instagram' => 'https://www.instagram.com/salmabouabidi_19/',
                    'linkedin' => 'https://www.linkedin.com/in/selma-bouabidi-938b08237/'
                ]
            ],
            [
                'name' => 'Wided Oueslati',
                'position' => 'Captain of Comedy',
                'image' => 'assets/img/team/wided.webp',
                'social' => [
                    'facebook' => 'https://www.facebook.com/profile.php?id=100069807314531',
                    'instagram' => 'https://www.instagram.com/wided_ouesletyy/',
                    'linkedin' => ''
                ]
            ],
            [
                'name' => 'Skander Ben Achour',
                'position' => 'Head of Snack Acquisition',
                'image' => 'assets/img/team/skander.webp',
                'social' => [
                    'facebook' => 'https://www.facebook.com/skander.benachour.1',
                    'instagram' => 'https://www.instagram.com/skander_ben_achour/',
                    'linkedin' => ''
                ]
            ], [
                'name' => 'Yasmine Riahi',
                'position' => 'Vibe Manager',
                'image' => 'assets/img/team/yasmine.webp',
                'social' => [
                    'facebook' => 'https://www.facebook.com/windy.jasmin.12072001',
                    'instagram' => '',
                    'linkedin' => 'https://www.linkedin.com/in/yassmine-riahi-4685bb250/'
                ]
            ]
        ];
        return $this->render('/index.html.twig', [
            'controller_name' => 'HomeController',
            'teamMembers' => $teamMembers
        ] );
    }
}
