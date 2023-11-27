<?php

namespace App\Service\Article;

use App\Repository\ArticleRepository;
use Symfony\Contracts\Service\Attribute\Required;

class ArticleFinder
{
	#[Required]
	public ArticleRepository $articleRepository;

	public function findAll() {
		return $this->articleRepository->findAll();
	}
	public function findAllDesc() {
		return $this->articleRepository->findAllDesc();
	}

	public function findLastArticles($max = 3) {
		return $this->articleRepository->findLastArticles($max);
	}

}