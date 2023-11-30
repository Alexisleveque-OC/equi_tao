<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UploadImageUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                    'label' => 'Changer mon image de profil',
                    'help' => 'Taille maximum : 4Mo.
        Types d\'image acceptés : .jpg, .jpeg, .png, .gif',
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '4096K',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/gif'
                            ]
                        ])
                    ]

                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
