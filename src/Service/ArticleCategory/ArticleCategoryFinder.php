<?php

namespace App\Service\ArticleCategory;

use App\Entity\ArticleCategory;
use App\Repository\ArticleCategoryRepository;
use Symfony\Contracts\Service\Attribute\Required;

class ArticleCategoryFinder
{
	#[Required]
	public ArticleCategoryRepository $articleCategoryRepository;

	public function findAll() {
		return $this->articleCategoryRepository->findAll();
	}

	public function findById( int $id): ArticleCategory {
		return $this->articleCategoryRepository->find($id);
	}

}