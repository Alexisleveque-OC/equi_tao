<?php

namespace App\Controller;

use App\Entity\ArticleCategory;
use App\Form\ArticleCategoryType;
use App\Form\DeleteConfType;
use App\Service\ArticleCategory\ArticleCategoryFinder;
use App\Service\ArticleCategory\CreateCategory;
use App\Service\ArticleCategory\DeleteCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

#[Route(path: '/categories', name: 'app_article_category.')]
class ArticleCategoryController extends AbstractController
{
	#[Required]
	public ArticleCategoryFinder $articleCategoryFinder;

	#[Route('/create/{fromList}', name: 'create')]
	public function index(Request $request, CreateCategory $categoryService, bool $fromList = false): Response {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		$formCategory = $this->createForm(ArticleCategoryType::class);
		$formCategory->handleRequest($request);

		if ($formCategory->isSubmitted() && $formCategory->isValid()) {
			$categoryService->create($formCategory->getData());

			if ($fromList){
				return $this->redirectToRoute('app_article_category.list');
			}

			return $this->redirectToRoute('app_article.create');
		}

		return $this->render('article_category/index.html.twig', [
		]);
	}

	#[Route('/update/{id}', name: 'update')]
	public function update(Request $request, ArticleCategory $category, CreateCategory $categoryService): Response {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		$formCategory = $this->createForm(ArticleCategoryType::class, $category);
		$formCategory->handleRequest($request);

		if ($formCategory->isSubmitted() && $formCategory->isValid()) {
			$categoryService->create($formCategory->getData());
			return $this->redirectToRoute('app_article_category.list');
		}

		return $this->render('article_category/update.html.twig', [
			'formCategory' => $formCategory->createView(),
			'category' => $category,
		]);
	}

	#[Route('/list', name: 'list')]
	public function list(): Response {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		$formCategory = $this->createForm(ArticleCategoryType::class);

		$categories = $this->articleCategoryFinder->findAll();
		foreach ($categories as $category) {
			$formDelete = $this->createForm(DeleteConfType::class, null, [
				'action' => $this->generateUrl('app_article_category.delete', ['id' => $category->getId()]),
			]);
			$category->formDelete = $formDelete->createView();

			$formUpdateCategory = $this->createForm(ArticleCategoryType::class, $category, [
				'action' => $this->generateUrl('app_article_category.update', ['id' => $category->getId()]),
			]);
			$category->formUpdate = $formUpdateCategory->createView();
		}

		return $this->render('article_category/list.html.twig', [
			'formCategory' => $formCategory->createView(),
			'categories' => $categories,
		]);
	}

	#[Route('/delete/{id}', name: 'delete')]
	public function delete(Request $request, ArticleCategory $category, DeleteCategory $deleteCategory): Response {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');

		$formDelete = $this->createForm(DeleteConfType::class);
		$formDelete->handleRequest($request);

		if ($formDelete->isSubmitted() && $formDelete->isValid()) {
			$deleteCategory->delete($category);
		}

		return $this->redirectToRoute('app_article_category.list');
	}
}
