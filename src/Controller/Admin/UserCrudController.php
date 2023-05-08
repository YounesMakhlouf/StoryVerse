<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $roles = ['ROLE_ADMIN', 'ROLE_USER'];

        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('username')
                ->setSortable(false),
            EmailField::new('email')
                ->hideOnIndex(),
            TextField::new('FullName')
                ->hideOnForm(),
            TextField::new('firstName')
                ->onlyOnForms(),
            TextField::new('lastName')
                ->onlyOnForms(),
            BooleanField::new('IsVerified')
                ->renderAsSwitch(false),
            DateField::new('createdAt')
                ->hideOnForm()
                ->setLabel('Registration date'),
            ChoiceField::new('roles')
                ->setChoices(array_combine($roles, $roles))
                ->allowMultipleChoices()
                ->renderExpanded()
                ->renderAsBadges()
                ->setSortable(false),

        ];
    }

}
