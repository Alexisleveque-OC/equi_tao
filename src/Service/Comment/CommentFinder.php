<?php

namespace App\Service\Comment;

use App\Repository\CommentRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\Service\Attribute\Required;

class CommentFinder
{
	#[Required]
	public CommentRepository $commentRepository;

	public function findAllInvalidate() {
		return $this->commentRepository->findAllInvalidate();
	}

	/**
	 * @throws NonUniqueResultException
	 */
	public function findOneById(int $id) {
		return $this->commentRepository->findOneById($id);
	}

}