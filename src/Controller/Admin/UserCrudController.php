<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFQCN(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $addAdminRole = Action::new('Add as admin')
            ->addCssClass('btn btn-success')
            ->setIcon('fa-solid fa-user-plus')
            ->displayAsButton()
            ->linkToCrudAction('addAdminRole')
            ->setTemplatePath('admin/add_admin.html.twig')
            ->displayIf(fn(User $user) => !in_array("ROLE_ADMIN", $user->getRoles()));

        $removeAdminRole = Action::new('Deprive Admin Role')
            ->addCssClass('btn btn-danger')
            ->setIcon('fa-solid fa-user-minus')
            ->displayAsButton()
            ->linkToCrudAction('removeAdminRole')
            ->setTemplatePath('admin/add_admin.html.twig')
            ->displayIf(fn(User $user) => in_array("ROLE_ADMIN", $user->getRoles()));

        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, $addAdminRole)
            ->add(Crud::PAGE_INDEX, $removeAdminRole)
            ->add(Crud::PAGE_DETAIL, $addAdminRole)
            ->add(Crud::PAGE_DETAIL, $removeAdminRole);
    }

    public function configureFields(string $pageName): iterable
    {
        $roles = ['ROLE_ADMIN', 'ROLE_USER'];

        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('username')->setSortable(false),
            EmailField::new('email')->hideOnIndex(),
            TextField::new('FullName')->hideOnForm(),
            TextField::new('firstName')->onlyOnForms(),
            TextField::new('lastName')->onlyOnForms(),
            BooleanField::new('IsVerified')->renderAsSwitch(false),
            DateField::new('createdAt')->hideOnForm()->setLabel('Registration date'),
            ChoiceField::new('roles')
                ->setChoices(array_combine($roles, $roles))
                ->allowMultipleChoices()
                ->renderExpanded()
                ->renderAsBadges()
                ->setSortable(false),
        ];
    }

    public function addAdminRole(AdminContext $adminContext, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator): RedirectResponse
    {
        $user = $adminContext->getEntity()->getInstance();
        return $this->updateRole($user, ["ROLE_ADMIN"], $entityManager, $adminUrlGenerator);
    }

    private function updateRole(User $user, array $roles, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator): RedirectResponse
    {
        $user->setRoles($roles);
        $entityManager->persist($user);
        $entityManager->flush();

        $targetUrl = $adminUrlGenerator
            ->setController(self::class)
            ->setAction(Crud::PAGE_INDEX)
            ->setEntityId($user->getId())
            ->generateUrl();

        return $this->redirect($targetUrl);
    }

    public function removeAdminRole(AdminContext $adminContext, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator): RedirectResponse
    {
        $user = $adminContext->getEntity()->getInstance();
        return $this->updateRole($user, ["ROLE_USER"], $entityManager, $adminUrlGenerator);
    }
}
