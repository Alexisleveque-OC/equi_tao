<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Service\Comment\CommentFinder;
use App\Service\Comment\CommentValidatorService;
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
	public CommentValidatorService $commentValidator;

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
	#[Route(path: '/validate/{id<\d+>}', name: 'validate')]
	public function validate(int $id) {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		/* @var Comment $comment */
		$comment = $this->commentFinder->findOneById($id);

		if (!$comment) {
			$e ="Le commentaire n'existe pas";
			throw $this->createNotFoundException($e);
		}

		$this->commentValidator->validateComment($comment);
		$this->addFlash('success', "Le commentaire de {$comment->getUser()->getUsername()} a bien été validé");

		return $this->redirectToRoute('app_comment.list_invalidate');
	}

}