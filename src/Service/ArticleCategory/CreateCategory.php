<?php

namespace App\Service\ArticleCategory;

use App\Entity\ArticleCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class CreateCategory
{
	#[Required]
	public EntityManagerInterface $manager;
	#[Required]
	public SluggerInterface $slugger;

	public function create(ArticleCategory $articleCategory) {

		$slug = $this->slugger->slug($articleCategory->getName());
		$articleCategory->setSlug($slug);

		$this->manager->persist($articleCategory);
		$this->manager->flush();
	}
}