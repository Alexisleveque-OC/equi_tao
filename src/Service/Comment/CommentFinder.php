<?php

namespace App\Service\Comment;

use App\Repository\CommentRepository;
use Symfony\Contracts\Service\Attribute\Required;

class CommentFinder
{
	#[Required]
	public CommentRepository $commentRepository;

	public function findAllInvalidate() {
		return $this->commentRepository->findAllInvalidate();
	}

}