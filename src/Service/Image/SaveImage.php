<?php

namespace App\Service\Image;

use App\Entity\Image;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class SaveImage
{
	private EntityManagerInterface $manager;
	private string $imageDirectory;

	public function __construct(EntityManagerInterface $manager, string $imageDirectory) {
		$this->manager = $manager;
		$this->imageDirectory = $imageDirectory;
	}

	public function saveImageOnUser(User $user, Image $image) {
		if ($oldImage = $user->getImage()) {
			$this->deleteImageOnServer($oldImage);
			$this->manager->remove($oldImage);
			$this->manager->flush();
		}

		$image->setUrl("/Image/{$image->getName()}")
			->setAlt("Image de profil de l'utilisateur : {$user->getUsername() }")
			->setUser($user);

		$this->manager->persist($image);


		$this->manager->flush();
		return $image;
	}

	public function deleteImageOnServer(Image $image) {
		$image->setUser(null)
			->setArticle(null);
		try {
			unlink($this->imageDirectory . '/' . $image->getName());
		} finally {
			return true;
		}

	}
}