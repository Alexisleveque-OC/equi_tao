<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleCategoryType;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\ImageRepository;
use App\Service\Article\ArticleFinder;
use App\Service\Article\ArticleUpdaterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;


#[Route(path: '/articles', name: 'app_article.')]
class ArticleController extends AbstractController
{
	#[Required]
	public ArticleRepository $articleRepository;
	#[Required]
	public ImageRepository $imageRepository;

	/**
	 * @throws \Exception
	 */
	#[Route('/create', name: 'create')]
	#[Route('/mettre-a-jour/{id<\d+>}-{article_slug}', name: 'update')]
	public function create(Request $request, ArticleUpdaterService $articleUpdaterService, Article $article = null): Response {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');

		if ($article && $oldImages = $this->imageRepository->findByArticle($article)) {
			foreach ($oldImages as $image) {
				$article->addImage($image);
			}
		}

		if (!$article) {
			$article = new Article();
		}

		$formArticle = $this->createForm(ArticleType::class, $article);
		if ($oldImages) {
			$formArticle
				->get('images')
				->setData($oldImages);
		}

		$formCategory = $this->createForm(ArticleCategoryType::class);
		$formArticle->handleRequest($request);

		if ($formArticle->isSubmitted() && $formArticle->isValid()) {

			$article = $articleUpdaterService->create($formArticle->getData(),
				$this->getUser(),
				$formArticle->get("images")->getData());

			return $this->redirectToRoute('app_article.show', [
				'article_category_slug' => $article->getCategory()->getSlug(),
				'id' => $article->getId(),
				'article_slug' => $article->getSlug(),
			]);
		}

		return $this->render('article/create.html.twig', [
			'formArticle' => $formArticle->createView(),
			'formCategory' => $formCategory->createView(),
			'editMode' => $article->getId() !== null,
			'article' => $article,
		]);
	}

	#[Route('/list', name: 'list')]
	public function list(ArticleFinder $finder): Response {
		$articles = $finder->findAllDesc();

		return $this->render('article/list.html.twig', [
			'articles' => $articles
		]);
	}

	#[Route('/{article_category_slug}/{id<\d+>}-{article_slug}', name: 'show')]
	public function show(Article $article): Response {
		dump($article);
		$images = $this->imageRepository->findBy(['article' => $article]);
		if ($images) {
			foreach ($images as $image) {
				$article->addImage($image);
			}
		}
		return $this->render('article/show.html.twig', [
			'article' => $article
		]);
	}
}
