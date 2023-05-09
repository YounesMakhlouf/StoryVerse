<?php

namespace App\Controller\Admin;

use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class ReportedStoryCrudController extends StoryCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, 'Reported Stories');
    }
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, \EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection $filters): QueryBuilder
    {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.IsReported = :report')
            ->setParameter('report', true);
    }
        public function configureActions(Actions $actions): Actions
    {
        $acceptReport= $this->createAction('Accept report','fa-solid fa-check','deleteStory');
        $rejectReport= $this->createAction('Reject report','fa-solid fa-trash','rejectReport');
        return $actions
            ->disable('DELETE')
            ->add(Crud::PAGE_INDEX, $acceptReport)
            ->add(Crud::PAGE_INDEX,$rejectReport);

    }

    /**
     * @throws \Exception
     */
    public function deleteStory(StoryRepository $storyRepository,AdminContext $adminContext,AdminUrlGenerator $adminUrlGenerator)
    {
        $story=$adminContext->getEntity()->getInstance();
        $storyRepository->deleteStoryWithContributionsAndComments($story->getId());
        $targetUrl = $adminUrlGenerator
            ->setController(self::class)
            ->setAction(Crud::PAGE_INDEX)
            ->setEntityId($story->getId())
            ->generateUrl();
        return $this->redirect($targetUrl);
    }

    public function rejectReport(AdminContext $adminContext, EntityManagerInterface $entityManager,AdminUrlGenerator $adminUrlGenerator)
    {
        $story=$adminContext->getEntity()->getInstance();
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


    public function createAction($name,$icon,$action){
        return  Action::new($name)
            ->setIcon($icon)
            ->displayAsButton()
            ->linkToCrudAction($action)
            ->setTemplatePath('admin/add_admin.html.twig');

    }

}