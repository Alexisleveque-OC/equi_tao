<?php

namespace App\Form\User;

use App\Entity\User;
use App\Validator\Security\OldPasswordMatch;
use App\Validator\Security\PasswordReinforcer;
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
                    'required' => true,
					'help' => 'Le mot de passe doit contenir au moins 8 caractères dont une majuscule, une minuscule, un chiffre et un caractère spécial',
					'constraint' => [
						new PasswordReinforcer()
					]
				]
            );

        if (!$options['byAdmin']) {
            $builder->add('password_old', PasswordType::class, [
                    'label' => 'Mot de passe actuel',
                    'required' => true,
					'constraints' => [new OldPasswordMatch($options['user'])]
                ]
            );
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setRequired(['byAdmin', 'user']);
        $resolver->setAllowedTypes('byAdmin', 'boolean');
        $resolver->setAllowedTypes('user', User::class);
    }
}
