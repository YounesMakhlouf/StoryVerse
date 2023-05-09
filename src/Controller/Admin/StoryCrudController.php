<?php

namespace App\Controller\Admin;

use App\Entity\Story;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
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
        return [
            IdField::new('id')
                ->setSortable(false),
            TextField::new('title')
            ->setSortable(false),
            IntegerField::new('Total likes')
                ->setTemplatePath('admin/field/votes.html.twig'),
            DateField::new('createdAt'),
            TextField::new('Genre'),
            TextareaField::new('StoryContent')
            ->onlyOnDetail()
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return parent::configureFilters($filters)
            ->add('createdAt')
            ->add(ChoiceFilter::new('genre')
                ->setChoices(['Romance'=>'Romance','Mystery'=>'Mystery','Horror'=>'Horror','Fiction'=>'Fiction','Comedy'=>'Comedy','Drama'=>'Drama'])
            );
    }

}
