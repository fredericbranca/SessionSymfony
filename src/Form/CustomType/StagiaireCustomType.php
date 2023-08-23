<?php

namespace App\Form\CustomType;

use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Définition d'un type de formulaire personnalisé pour l'entité Stagiaire
class StagiaireCustomType extends AbstractType
{
    // Cette méthode construit le formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Ajoute un champ pour le nom du stagiaire
            // Type Text par défaut car on passe null en 2ème argument
            // L'attribut readonly est défini pour rendre le champ en lecture seule
            ->add('nom', null, ['attr' => ['readonly' => true]])

            // Idem, ajoute un champ pour le prénom et téléphone, en lecture seule
            ->add('prenom', null, ['attr' => ['readonly' => true]])
            ->add('telephone', null, ['attr' => ['readonly' => true]])

            // ->add('session_stagiaire')
        ;
    }

    // Cette méthode configure les options pour le formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Définit la classe de données par défaut pour ce formulaire comme étant Stagiaire
        // Cela signifie que ce formulaire est destiné à créer ou à modifier des objets de type Stagiaire
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}
