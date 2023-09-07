<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $lastUsername = $options['last_username'];

        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => [
                    'name' => 'email',
                    'value' => $lastUsername
                ] 
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'attr' => [
                    'name' => 'password',
                ]
            ])
            ->add('valider', SubmitType::class, [
                'label' => 'Se connecter',
                'attr' => [
                    'class' => 'btn'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'last_username' => []
        ]);
    }
}
