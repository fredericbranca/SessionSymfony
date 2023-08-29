<?php

namespace App\Form;

use App\Entity\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('formation', null, [
                'required' => true
            ])
            ->add('date_debut', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'required' => true
            ])
            ->add('date_fin', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'required' => true
            ])
            ->add('nb_place', null, [
                'required' => true
            ])
            ->add('Valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
