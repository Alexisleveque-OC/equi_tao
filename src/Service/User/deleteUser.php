<?php

namespace App\Service\User;

use App\Entity\User;
use App\Service\Image\SaveImage;
use Doctrine\ORM\EntityManagerInterface;

class deleteUser
{
    private EntityManagerInterface $manager;
    private mixed $saveImage;

    public function __construct(EntityManagerInterface $manager, SaveImage $saveImage)
    {
        $this->manager = $manager;
        $this->saveImage = $saveImage;
    }

    public function deleteUser(User $user)
    {
        $image = $user->getImage();
        if ($image) {
            $this->saveImage->deleteImageOnServer($image);
        }
        $this->manager->remove($user);
        $this->manager->flush();

    }

}