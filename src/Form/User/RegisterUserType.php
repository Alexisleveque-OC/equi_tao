<?php

namespace App\Form\User;

use App\Entity\User;
use App\Validator\Security\PasswordReinforcer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;

class RegisterUserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void {
		$builder
			->add('username', TextType::class, [
				'label' => 'Nom d\'utilisateur',
				'attr' => [
					'placeholder' => "Nom d'utilisateur"
				],
				'constraints' => [
					new Length(min: 5, minMessage: "Le nom d'utilisateur doit contenir au moins {{ limit }} caractères")
				]
			])
			->add('email', EmailType::class, [
					'label' => 'Adresse email',
					'attr' => [
						'placeholder' => "Adresse email"
					],
					'constraints' => [
						new EmailConstraint(message: "L'adresse email n'est pas valide"),
					]
				]
			)
			->add('password', RepeatedType::class, [
				'type' => PasswordType::class,
				'first_options' => ['label' => 'Mot de passe', 'attr' => ['placeholder' => 'Mot de passe']],
				'second_options' => ['label' => 'Confirmation de mot de passe', 'attr' => ['placeholder' => 'Confirmation du mot de passe']],

				'help' => 'Le mot de passe doit contenir au moins 8 caractères dont une majuscule, une minuscule, un chiffre et un caractère spécial',
				'constraints' => [
					new PasswordReinforcer()
				]
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void {
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
}
