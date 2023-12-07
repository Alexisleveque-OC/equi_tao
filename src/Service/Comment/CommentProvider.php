<?php

namespace App\Service\Comment;

use App\Repository\CommentRepository;

class CommentProvider
{
	private CommentRepository $commentRepository;

	public function __construct(CommentRepository $commentRepository)
	{
		$this->commentRepository = $commentRepository;
	}

	public function countNoValidatedComment() {
		return count($this->commentRepository->findAllInvalidate());
	}

}