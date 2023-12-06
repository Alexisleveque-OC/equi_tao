<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Service\Comment\CommentFinder;
use App\Service\Comment\CommentService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

#[Route(path: '/comments', name: 'app_comment.')]
class CommentController extends AbstractController
{
	#[Required]
	public CommentFinder $commentFinder;
	#[Required]
	public CommentService $commentService;

	#[Route(path: '/liste_invalidate', name: 'list_invalidate')]
	public function listInvalidate() {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');

		$comments = $this->commentFinder->findAllInvalidate();

		return $this->render('comment/list_invalidate.html.twig', [
			'comments' => $comments
		]);
	}

	/**
	 * @throws NonUniqueResultException
	 */
	#[Route(path: '/validate/{comment}', name: 'validate')]
	public function validate(Comment $comment) {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');

		$this->commentService->validateComment($comment);
		$this->addFlash('success', "Le commentaire de {$comment->getUser()->getUsername()} a bien été validé");

		return $this->redirectToRoute('app_comment.list_invalidate');
	}

	#[Route(path: '/supprimer/{comment}-{inArticle}', name: 'delete')]
	public function delete(Comment $comment = null, bool $inArticle = false) {

		if ($this->isGranted('COMMENT_DELETE', $comment) === false) {
			$this->addFlash('danger', "Vous n'avez pas le droit de supprimer ce commentaire");
			return $this->redirectToRoute('app_article.show', [
				'article_category_slug' => $comment->getArticle()->getCategory()->getSlug(),
				'id' => $comment->getArticle()->getId(),
				'article_slug' => $comment->getArticle()->getSlug(),
			]);
		}

		$this->commentService->deleteComment($comment);
		$this->addFlash('danger', "Le commentaire de {$comment->getUser()->getUsername()} a bien été supprimé");

		if ($inArticle) {
			return $this->redirectToRoute('app_article.show', [
				'article_category_slug' => $comment->getArticle()->getCategory()->getSlug(),
				'id' => $comment->getArticle()->getId(),
				'article_slug' => $comment->getArticle()->getSlug(),
			]);
		}

		return $this->redirectToRoute('app_comment.list_invalidate');
	}


}