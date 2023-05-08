<?php

namespace App\Controller\Admin;

use App\Repository\StoryRepository;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
        private UserRepository $userRepository;
        private StoryRepository $storyRepository;


    public function __construct(UserRepository $userRepository, StoryRepository $storyRepository)
    {
        $this->userRepository = $userRepository;
        $this->storyRepository = $storyRepository;

    }
    #[IsGranted('ROLE_ADMIN')]

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $nbUsers = $this->getInfo('u', $this->userRepository ,'COUNT(u.id) ');
        $nbStories=$this->getInfo('s',$this->storyRepository,'COUNT(s.id) ');
//        $interactions=$this->getInfo('s',$this->storyRepository,'SUM(s.likes)');
        return $this->render('admin/admin.html.twig',[
            'story'=>$nbStories,
            'users'=>$nbUsers,
            'interaction'=>'3'
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
        yield MenuItem::linkToCrud('Reported Stories', "fa-solid fa-triangle-exclamation",Story::class)
            ->setController(ReportedStoryCrudController::class);
        ;


    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user);
//            ->setAvatarUrl($user->getAvatarUrl());
        //TODO: nuncommentiha ki nmergi w ywalli l user 3andou l avatar li 3amlettou wided
//            ->addMenuItems([
//                MenuItem::linkToUrl('My Profile', 'fas fa-user', $this->generateUrl('app_profile'))]);
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
    public function getInfo($alias,$repository,$select){
        $queryBuilder = $repository->createQueryBuilder($alias);
        $queryBuilder
            ->select($select);
        $result = ($queryBuilder->getQuery()->getSingleScalarResult());
        return($result);

    }


}
