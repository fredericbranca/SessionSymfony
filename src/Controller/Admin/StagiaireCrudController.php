<?php

namespace App\Controller\Admin;

use App\Entity\Stagiaire;
use App\Form\SessionType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Doctrine\ORM\Mapping\Builder\AssociationBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
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
        return [
            IdField::new('id')
                ->setDisabled(),
            TextField::new('nom'),
            TextField::new('prenom'),
            TelephoneField::new('telephone'),
            // ArrayField::new('session_stagiaire')
            //     ->onlyOnForms()
            //     ->setDisabled(),
            // AssociationField::new('session_stagiaire')
            //     ->onlyOnIndex(),
            CollectionField::new('session_stagiaire')
                ->setEntryType(SessionType::class)
                ->setDisabled()
        ];
    }
}
