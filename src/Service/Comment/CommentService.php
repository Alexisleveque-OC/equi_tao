<?php

namespace App\Service\Comment;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Service\Article\ArticleFinder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Service\Attribute\Required;

class CommentService
{
	#[Required]
	public EntityManagerInterface $entityManager;

	#[Required]
	public ArticleFinder $articleFinder;

	public function validateComment(Comment $comment) {
		$comment->setIsValidate(true);
		$this->entityManager->persist($comment);
		$this->entityManager->flush();
	}

	public function deleteComment(Comment $comment) {
		$this->entityManager->remove($comment);
		$this->entityManager->flush();
	}

	public function createComment(Comment $comment, UserInterface|User $user, Article $article) {

		if (in_array('ROLE_ADMIN', $user->getRoles())) {
			$comment->setIsValidate(true);
		} else {
			$comment->setIsValidate(false);
		}

		$comment->setCreationDate(new \DateTime())
			->setUser($user)
			->setArticle($article)
		;
//$article->addComment($comment);
//$this->entityManager->persist($article);

		$this->entityManager->persist($comment);
		$this->entityManager->flush();

		return $comment;

	}

}