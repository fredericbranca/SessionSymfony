<?php

namespace App\Controller\Admin;

use App\Entity\Stagiaire;
use App\Form\SessionType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Doctrine\ORM\Mapping\Builder\AssociationBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class StagiaireCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Stagiaire::class;
    }

    public function configureFields(string $pageName): iterable
    {
            $fields = [
                IdField::new('id')
                    ->setDisabled(),
                TextField::new('nom'),
                TextField::new('prenom'),
                TelephoneField::new('telephone'),
                AssociationField::new('session_stagiaire')
                    ->setLabel('Nombre de session inscrit')
                    ->onlyOnIndex()
            ];

            if ($pageName == Crud::PAGE_EDIT) {

                $fields[] = CollectionField::new('session_stagiaire')
                    ->onlyOnForms()
                    ->setLabel('Session')
                    ->setEntryType(SessionType::class)
                    ->setDisabled();
            }

            return $fields;
    }
}
