<?php
namespace App\Service\ArticleCategory;

use App\Repository\ArticleCategoryRepository;
use Symfony\Contracts\Service\Attribute\Required;

class CategoryProvider
{
	private ArticleCategoryRepository $categoryRepository;

	public function __construct(ArticleCategoryRepository $categoryRepository)
	{
		$this->categoryRepository = $categoryRepository;
	}

	public function getCategories()
	{
		return $this->categoryRepository->findAll();
	}
}