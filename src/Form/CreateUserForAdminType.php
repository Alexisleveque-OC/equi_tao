<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateUserForAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('username')
//            ->add('password', TextType::class, [
//                'password' => ['label' => 'Mot de passe actuel'],
//                'required' => false
//            ])
//            ->add('password', RepeatedType::class, [
//                'type' => PasswordType::class,
//                'first_options' => ['label' => 'Nouveau mot de passe', 'attr' => ['placeholder' => 'Nouveau mot de passe']],
//                'second_options' => ['label' => 'Confirmation du nouveau mot de passe', 'attr' => ['placeholder' => 'Confirmation du nouveau mot de passe']],
//                'required' => false])
            ;
        if ($options['withRoleSelector']) {
            $builder
                ->add('roles', ChoiceType::class, [
                        "multiple" => true,
                        "expanded" => true,
                        "label" => false,
                        'choices' => [
                            'Administrateur' => 'ROLE_ADMIN'
                        ]
                    ]
                );
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setRequired('withRoleSelector');
        $resolver->setAllowedTypes('withRoleSelector','boolean');
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
