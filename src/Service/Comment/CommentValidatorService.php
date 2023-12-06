<?php

namespace App\Service\Comment;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class CommentValidatorService
{
	#[Required]
	public EntityManagerInterface $entityManager;

	public function validateComment(Comment $comment) {
		$comment->setIsValidate(true);
		$this->entityManager->persist($comment);
		$this->entityManager->flush();
	}

}