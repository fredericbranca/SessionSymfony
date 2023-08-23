<?php

namespace App\Form\CustomType;

use App\Entity\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionCustomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nb_place', null, ['attr' => ['readonly' => true]])
            ->add('date_debut', null, ['attr' => ['readonly' => true]])
            ->add('date_fin', null, ['attr' => ['readonly' => true]])
            ->add('stagiaires', null, ['attr' => ['readonly' => true]])
            ->add('formation', null, ['attr' => ['readonly' => true]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
