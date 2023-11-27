<?php

namespace App\Service\Article;

use App\Entity\Article;
use App\Entity\User;
use App\Service\Image\SaveImage;
use App\Service\Image\UploadImage;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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
	public function create(Article $article, User $user, $newImages): Article {

		$slug = $this->slugger->slug($article->getTitle());

		if (!$article->getId()) {
			$article->setCreationDate(new DateTime());
		} else {
			$article->setLastUpdateDate(new DateTime());
		}
		$article->setSlug($slug)
			->setUser($user);
		$this->manager->persist($article);

		$oldImages = $article->getImages();
		$article = $this->compareImages($oldImages->getValues(), $newImages, $article);

		foreach ($article->getImages() as $image){
			if (!$image->getId()){
				$image->setArticle($article)
					->setUrl('/Image/' . $image->getName())
					->setAlt("image de l'article : " . $article->getTitle());

				$this->manager->persist($image);
			}
		}

		$this->manager->flush();


		return $article;
	}

	/**
	 * @throws \Exception
	 */
	private function compareImages(array $oldImages, array $newImages, Article $article): Article {

		foreach ($oldImages as $oldImage) {
			if (!in_array($oldImage, $newImages)){
				$article->removeImage($oldImage);
				$this->saveImage->deleteImageOnServer($oldImage);
				$this->manager->remove($oldImage);
				$this->manager->flush();
			}
		}
		foreach ($newImages as $newImage){
			if (!in_array($newImage, $oldImages)){
				$newImage = $this->uploadImage->saveImageOnServer($newImage);
				$article->addImage($newImage);
			}
		}

		return $article;
	}

}