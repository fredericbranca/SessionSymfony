<?php

namespace App\Form;

use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('prenom', TextType::class, [
                'required' => true,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('telephone', TextType::class, [
                'required' => true,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}
