<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Programme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Récupère de la liste des modules non programmés passée en option lors de la création du formulaire
        $modulesNonProgrammes = $options['modules_non_programmes'];

        $builder
            ->add('nb_jour')
            ->add('module', EntityType::class, [
                'class' => Module::class,
                'choices' => $modulesNonProgrammes, // liste des modules non programmés
                'placeholder' => 'Choisir un module'
            ])
            ->add('Valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
            'modules_non_programmes' => [] // initialisation de la liste dans un tableau vide pour éviter les erreurs
        ]);
    }
}
