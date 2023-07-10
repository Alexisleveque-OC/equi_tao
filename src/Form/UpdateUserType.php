<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('username')
            ->add('password_old', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'required' => false
            ])
            ->add('password_new', RepeatedType::class, [
                    'type' => \Symfony\Component\Form\Extension\Core\Type\PasswordType::class,
                    'first_options' => ['label' => 'Nouveau mot de passe', 'attr' => ['placeholder' => 'Mot de passe']],
                    'second_options' => ['label' => 'Confirmation du nouveau mot de passe', 'attr' => ['placeholder' => 'Confirmation du mot de passe']],
                    'required' => false]
            );

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
