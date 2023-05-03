<?php

namespace App\Controller\Admin;

use App\Repository\StoryRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use App\Entity\Story;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractDashboardController
{
//    #[IsGranted('ROLE_ADMIN')]
        private UserRepository $userRepository;
        private StoryRepository $storyRepository;

        private ChartBuilderInterface $chartBuilder;

    public function __construct(UserRepository $userRepository, StoryRepository $storyRepository,ChartBuilderInterface $chartBuilder)
    {
        $this->chartBuilder = $chartBuilder;
        $this->userRepository = $userRepository;
        $this->storyRepository = $storyRepository;

    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $queryBuilder = $this->userRepository->createQueryBuilder('u');
        $queryBuilder
            ->select('COUNT(u.id) ');
        $nbUsers = ($queryBuilder->getQuery()->getSingleScalarResult());
        $queryBuilder2 = $this->storyRepository->createQueryBuilder('s');
        $queryBuilder2
            ->select('COUNT(s.id) ');
        $nbStories=($queryBuilder2->getQuery()->getSingleScalarResult());
        $queryBuilder3= $this->storyRepository->createQueryBuilder('s');
        $queryBuilder3
            ->select('SUM(s.likes) ');
        $interactions=($queryBuilder3->getQuery()->getSingleScalarResult());



        return $this->render('admin/admin.html.twig',[
            'story'=>$nbStories,
            'users'=>$nbUsers,
            'interaction'=>$interactions
        ]);


    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Story Verse');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Homepage', 'fas fa-home', $this->generateUrl('app_home'));
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard');
        yield MenuItem::linkToCrud('Users', 'fas fa-users',User::class);
        yield MenuItem::linkToCrud('Stories', "fa-solid fa-book-open",Story::class);

    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
//            ->setAvatarUrl($user->getAvatarUrl());
        //TODO: nuncommentiha ki nmergi w ywalli l user 3andou l avatar li 3amlettou wided
            ->addMenuItems([
                MenuItem::linkToUrl('My Profile', 'fas fa-user', $this->generateUrl('app_profile'))
            ]);
          }

    public function configureAssets(): Assets
    {
        $assets = parent::configureAssets();

        $assets->addWebpackEncoreEntry('admin');

        return $assets;
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
        ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }


}
