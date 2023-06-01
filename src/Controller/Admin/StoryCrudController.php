<?php

namespace App\Controller\Admin;

use App\Entity\Story;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class StoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Story::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->setLabel('ID')->setSortable(false);
        yield TextField::new('title')->setLabel('Title')->setSortable(false);
        yield IntegerField::new('totalLikes')->setLabel('Total Likes')->setTemplatePath('admin/field/votes.html.twig')->setSortable(false);
        yield DateField::new('createdAt')->setLabel('Created At');
        yield TextField::new('genre')->setLabel('Genre');
        yield TextareaField::new('storyContent')->setLabel('Story Content')->onlyOnDetail();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return parent::configureFilters($filters)
            ->add('createdAt')
            ->add(ChoiceFilter::new('genre')
                ->setChoices($this->getGenreChoices())
            );
    }

    private function getGenreChoices(): array
    {
        $storyGenres = $this->getParameter('story_genres');
        $choices = [];
        foreach ($storyGenres as $genre => $description) {
            $choices[$genre] = $genre;
        }

        return $choices;
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->remove(CRUD::PAGE_INDEX, Action::EDIT)
            ->remove(CRUD::PAGE_DETAIL, ACTION::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }
}