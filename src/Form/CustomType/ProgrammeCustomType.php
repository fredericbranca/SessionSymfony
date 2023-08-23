<?php

namespace App\Form\CustomType;

use App\Entity\Module;
use App\Entity\Categorie;
use App\Entity\Programme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgrammeCustomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('module', EntityType::class, [
            'class' => Module::class,
            'choice_label' => 'nom', // afficher le nom du module
        ])
        ->add('categorie', EntityType::class, [
            'class' => Categorie::class,
            'choice_label' => 'nom',
            'attr' => ['disabled' => true], // En mode lecture seule
            'mapped' => false, // Ce champ n'est pas mappé directement
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
