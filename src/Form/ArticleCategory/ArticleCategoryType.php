<?php

namespace App\Form\ArticleCategory;

use App\Entity\ArticleCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArticleCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
				'label' => 'Nom de la nouvelle catégorie',
				'attr' => [
					'placeholder' => "Nom de la nouvelle catégorie"
				],
				'constraints' => new NotBlank()
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticleCategory::class,
        ]);
    }
}
