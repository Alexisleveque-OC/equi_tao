<?php

namespace App\Service\Article;

use App\Entity\Article;
use App\Service\Image\SaveImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class ArticleDelete
{
	#[Required]
	public EntityManagerInterface $entityManager;

	#[Required]
	public SaveImage $saveImage;

	public function delete(Article $article) {

		$comments = $article->getComments();
		foreach ($comments as $comment) {
			$article->removeComment($comment);
			$this->entityManager->remove($comment);
		}

		$likes = $article->getLikes();
		foreach ($likes as $like) {
			$article->removeLike($like);
			$this->entityManager->remove($like);
		}


		$images = $article->getImages();
		foreach ($images as $image) {
			$article->removeImage($image);
			$this->saveImage->deleteImageOnServer($image);
			$this->entityManager->remove($image);
		}

		$article->setCategory(null);
		$article->setUser(null);

		$this->entityManager->remove($article);
		$this->entityManager->flush();

	}
}