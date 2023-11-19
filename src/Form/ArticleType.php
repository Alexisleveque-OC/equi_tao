<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ArticleType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void {
		$builder
			->add('title', TextType::class, [
				'label' => "Titre de l'article",
				'attr' => [
					'placeholder' => "Titre de l'article",
					'autofocus' => true
				],
				'constraints' => new NotNull()
			])
			->add('content', TextType::class, [
				'label' => "Contenu:",
				'attr' => [
					'placeholder' => "Contenu:",
				],
				'constraints' => new NotNull()
			])
			->add('category', EntityType::class, [
				'class' => ArticleCategory::class,
				'choice_label' => 'name',
				'constraints' => NotBlank::class
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void {
		$resolver->setDefaults([
			'data_class' => Article::class,
		]);
	}
}