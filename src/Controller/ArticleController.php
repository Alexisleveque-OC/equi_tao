<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleCategoryType;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\Article\ArticleFinder;
use App\Service\Article\ArticleUpdaterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route(path: '/articles', name: 'app_article.')]
class ArticleController extends AbstractController
{

	/**
	 * @throws \Exception
	 */
	#[Route('/create', name: 'create')]
    public function create(Request $request, ArticleUpdaterService $articleUpdaterService): Response
    {
		$editMode = false;
		$this->denyAccessUnlessGranted('ROLE_ADMIN');

		$formArticle = $this->createForm(ArticleType::class);
		$formCategory = $this->createForm(ArticleCategoryType::class);
		$formArticle->handleRequest($request);

		if ($formArticle->isSubmitted() && $formArticle->isValid()){
			$files = $request->files->all();

			$article = $articleUpdaterService->create($formArticle->getData(), $this->getUser(), $files);

			return $this->render('article/show.html.twig',[
				'article' => $article
			]);
		}

        return $this->render('article/create.html.twig', [
			'formArticle' => $formArticle->createView(),
			'formCategory' => $formCategory->createView(),
			'editMode' => $editMode,
        ]);
    }

	#[Route('/list', name: 'list')]
	public function list(ArticleFinder $finder): Response
	{
		$articles = $finder->findAll();

		return $this->render('article/list.html.twig', [
			'articles' => $articles
		]);
	}

	#[Route('/{article_category_slug}/{id<\d+>}-{article_slug}', name: 'show')]
	public function show( Article $article): Response
	{
		dump($article);
		return $this->render('article/show.html.twig', [
			'article' => $article
		]);
	}
}
