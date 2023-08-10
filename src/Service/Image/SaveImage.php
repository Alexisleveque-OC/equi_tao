<?php

namespace App\Service\Image;

use App\Entity\Image;
use App\Entity\User;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

class SaveImage
{
    private ImageRepository $imageRepository;
    private EntityManagerInterface $manager;
    private string $imageDirectory;

    public function __construct(EntityManagerInterface $manager, ImageRepository $imageRepository, string $imageDirectory)
    {
        $this->manager = $manager;
        $this->imageRepository = $imageRepository;
        $this->imageDirectory = $imageDirectory;
    }

    public function saveImageOnUser(User $user, File $file)
    {
        if ($oldImage = $user->getImage()){
            $this->deleteImageOnServer($oldImage);
            $this->manager->remove($oldImage);
            $this->manager->flush();
        }
        $image = new Image();
        $image->setName($file->getBasename())
            ->setUrl('/Image/'.$file->getFilename())
        ->setAlt("Image de profil de l'utilisateur : {$user->getUsername() }");
        $this->manager->persist($image);
        $user->setImage($image);
        $this->manager->persist($user);

        $this->manager->flush();

        return $image;
    }

    public function deleteImageOnServer(Image $image)
    {
        $image->setUser(null)
        ->setArticle(null);
        try {
            unlink($this->imageDirectory.'/'.$image->getName());
        } finally {
            return true;
        }

    }
}