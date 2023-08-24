<?php

namespace App\Controller\Admin;

use App\Entity\Session;
use App\Form\CustomType\ProgrammeCustomType;
use App\Form\CustomType\StagiaireCustomType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SessionCrudController extends AbstractCrudController
{
    // Retourne l'entité qui est gérée par ce CRUD controller
    public static function getEntityFqcn(): string
    {
        return Session::class;
    }

    // Configure les champs qui doivent être affichés et comment ils doivent être traités
    public function configureFields(string $pageName): iterable
    {
        $formationField = $pageName == Crud::PAGE_EDIT
            ? AssociationField::new('formation')->setDisabled()
            : AssociationField::new('formation');

        $fields = [
            // Affiche le champ ID de la session et désactive toute modification avec ->setDisabled() (ce champ n'est pas considéré dans les données soumises)
            IdField::new('id')->setDisabled(),

            $formationField,

            // Champs pour le nombre de places dans la session
            IntegerField::new('NbPlace'),

            // Champs pour la date de début et de fin de la session
            DateField::new('DateDebut'),
            DateField::new('DateFin'),

            // Champ de collection pour les stagiaires. Utilise le StagiereCustomType, permet la suppression des entrées
            CollectionField::new('stagiaires')
                ->onlyOnForms()
                ->setEntryType(StagiaireCustomType::class)
                ->allowDelete(true)

                // Option pour éviter la perte de données lors de la suppression d'un stagiaire de la collection : ça garantie que les méthodes addStagiaire() et removeStagiaire() sont toujours appelées lors d'un ajout ou d'une supression d'un élément
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),

            CollectionField::new('programmes')
                ->onlyOnForms()
                ->setEntryType(ProgrammeCustomType::class)
                ->allowAdd(true)
                ->allowDelete(true)
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),

            // Champs pour les stagiaires et programmes, uniquement affichés sur la page index. Ici ça affiche le nombre de stagière et de programme car, par défaut, EasyAdmin montre le nombre d'élément dans les relation to-many
            AssociationField::new('stagiaires')
                ->onlyOnIndex(),
            AssociationField::new('programmes')
                ->onlyOnIndex(),
        ];

        return $fields;
    }
}
