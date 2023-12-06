<?php

namespace App\Controller;

use App\Service\Comment\CommentFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

#[Route(path: '/comments', name: 'app_comment.')]
class CommentController extends AbstractController
{
	#[Required]
	public CommentFinder $commentFinder;

	#[Route(path: '/liste_invalidate', name: 'list_invalidate')]
	public function listInvalidate() {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');

		$comments = $this->commentFinder->findAllInvalidate();

		return $this->render('comment/list_invalidate.html.twig', [
			'comments' => $comments
		]);

	}

}