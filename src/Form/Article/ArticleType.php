<?php

namespace App\Form\Article;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use App\Form\UploadImageType;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
			->add('content', TinymceType::class, [
				'label' => "Contenu:",
				'attr' => [
					'placeholder' => "Contenu:",
				],
				'constraints' => new NotNull()
			])
			->add('category', EntityType::class, [
				'class' => ArticleCategory::class,
				'required' => false,
				'label' => 'Catégorie',
				'choice_label' => 'name',
				'constraints' => new NotNull()
			])
			->add('images', CollectionType::class, [
				'entry_type' => UploadImageType::class,
				'label' => 'Images',
				'prototype' => true,
				'allow_add' => true,
				'by_reference' => false,
				'allow_delete' => true,
				'mapped' => false,
				'entry_options' => [
					'label' => false,
					'required' => false,
				],
			])
		;}

	public function configureOptions(OptionsResolver $resolver): void {
		$resolver->setDefaults([
			'data_class' => Article::class,
		]);
	}
}
