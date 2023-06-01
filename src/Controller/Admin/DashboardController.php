<?php

namespace App\Controller\Admin;

use App\Entity\Story;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\StoryRepository;
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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private readonly UserRepository    $userRepository,
                                private readonly StoryRepository   $storyRepository,
                                private readonly CommentRepository $commentRepository)
    {
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $nbUsers = $this->getEntityCount($this->userRepository);
        $nbStories = $this->getEntityCount($this->storyRepository);
        $interactions = $this->getEntityCount($this->commentRepository);

        return $this->render('admin/admin.html.twig', [
            'story' => $nbStories,
            'users' => $nbUsers,
            'interaction' => $interactions,
        ]);
    }

    private function getEntityCount($repository): int
    {
        $queryBuilder = $repository->createQueryBuilder('e');
        $queryBuilder->select('COUNT(e.id)');
        $result = $queryBuilder->getQuery()->getSingleScalarResult();
        return (int) $result;
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('StoryVerse');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Homepage', 'fas fa-home', $this->generateUrl('app_home'));
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard');
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Stories', "fa-solid fa-book-open", Story::class);
        yield MenuItem::linkToCrud('Reported Stories', "fa-solid fa-triangle-exclamation", Story::class)
            ->setController(ReportedStoryCrudController::class);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
//            ->setAvatarUrl($user->getAvatar()) // TODO: nuncommentiha ki nmergi w ywalli l user 3andou l avatar li 3amlettou wided
            ->addMenuItems([
                MenuItem::linkToUrl('My Profile', 'fas fa-user', $this->generateUrl('app_profile', ['id' => $user->getId()]))
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