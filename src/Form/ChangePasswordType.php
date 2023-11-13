<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password_new', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'Nouveau mot de passe', 'attr' => ['placeholder' => 'Nouveau mot de passe']],
                    'second_options' => ['label' => 'Confirmation du nouveau mot de passe', 'attr' => ['placeholder' => 'Confirmation du nouveau mot de passe']],
                    'required' => true]
            );

        if (!$options['byAdmin']) {
            $builder->add('password_old', PasswordType::class, [
                    'label' => 'Mot de passe actuel',
                    'required' => true
                ]
            );
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setRequired('byAdmin');
        $resolver->setAllowedTypes('byAdmin', 'boolean');
    }
}
