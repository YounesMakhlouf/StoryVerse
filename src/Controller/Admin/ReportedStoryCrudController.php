<?php

namespace App\Controller\Admin;

use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ReportedStoryCrudController extends StoryCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, 'Reported Stories')
            ->showEntityActionsInlined();
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.IsReported = :report')
            ->setParameter('report', true);
    }

    public function configureActions(Actions $actions): Actions
    {
        $acceptReport = $this->createCrudAction('Accept report', 'fa-solid fa-check', 'deleteStory');
        $rejectReport = $this->createCrudAction('Reject report', 'fa-solid fa-trash', 'rejectReport');
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, $acceptReport)
            ->add(Crud::PAGE_INDEX, $rejectReport)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT);
    }

    public function createCrudAction(string $name, string $icon, string $action): Action
    {
        return Action::new($name)
            ->setIcon($icon)
            ->linkToCrudAction($action)
            ->setTemplatePath('admin/add_admin.html.twig');
    }

    /**
     * @throws Exception
     */
    public function deleteStory(StoryRepository $storyRepository, AdminContext $adminContext, AdminUrlGenerator $adminUrlGenerator): RedirectResponse
    {
        $story = $adminContext->getEntity()->getInstance();
        $storyRepository->deleteStoryWithContributionsAndComments($story->getId());

        $targetUrl = $adminUrlGenerator
            ->setController(self::class)
            ->setAction(Crud::PAGE_INDEX)
            ->setEntityId($story->getId())
            ->generateUrl();
        return $this->redirect($targetUrl);
    }

    public function rejectReport(AdminContext $adminContext, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator): RedirectResponse
    {
        $story = $adminContext->getEntity()->getInstance();
        $story->setIsReported(false);
        $entityManager->persist($story);
        $entityManager->flush();

        $targetUrl = $adminUrlGenerator
            ->setController(self::class)
            ->setAction(Crud::PAGE_INDEX)
            ->setEntityId($story->getId())
            ->generateUrl();
        return $this->redirect($targetUrl);
    }
}