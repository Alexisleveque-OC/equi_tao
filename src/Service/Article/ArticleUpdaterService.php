<?php

namespace App\Service\Article;

use App\Entity\Article;
use App\Entity\Image;
use App\Entity\User;
use App\Service\Image\SaveImage;
use App\Service\Image\UploadImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class ArticleUpdaterService
{
	#[Required]
	public EntityManagerInterface $manager;
	#[Required]
	public SluggerInterface $slugger;
	#[Required]
	public UploadImage $uploadImage;
	#[Required]
	public SaveImage $saveImage;

	/**
	 * @throws \Exception
	 */
	public function create(Article $article, User $user, $files): Article {

		$slug = $this->slugger->slug($article->getTitle());

		$article->setSlug($slug)
			->setCreationDate(new \DateTime())
			->setUser($user);
		$images = $files['article']['images'];

		foreach ($images as $file) {
			/** @var UploadedFile $file */
			$file = $this->uploadImage->saveImageOnServer($file['image']);
			$image = new Image();
			$image->setName($file->getFilename())
				->setArticle($article)
				->setUrl($file->getPath())
				->setAlt($file->getFilename());

			$article->addImage($image);
			$this->manager->persist($image);
		}

		$this->manager->persist($article);

		$this->manager->flush();


		return $article;
	}

}