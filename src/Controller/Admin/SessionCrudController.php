<?php

namespace App\Controller\Admin;

use App\Entity\Session;
use App\Form\StagiaireType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SessionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Session::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setDisabled(),
            TextField::new('formation')->setDisabled(),
            IntegerField::new('NbPlace'),
            DateField::new('DateDebut'),
            DateField::new('DateFin'),
            CollectionField::new('stagiaires')
                ->setEntryType(StagiaireType::class)
                ->setDisabled(),
            // CollectionField::new('programmes'),
            AssociationField::new('stagiaires')
                ->onlyOnIndex(),
            AssociationField::new('programmes')
                ->onlyOnIndex(),
        ];
    }

}
