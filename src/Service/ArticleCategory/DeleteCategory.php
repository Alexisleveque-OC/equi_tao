<?php

namespace App\Service\ArticleCategory;

use App\Entity\ArticleCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class DeleteCategory
{
	#[Required]
	public EntityManagerInterface $entityManager;

	public function delete(ArticleCategory $articleCategory) {
		$this->entityManager->remove($articleCategory);
		$this->entityManager->flush();
	}

}