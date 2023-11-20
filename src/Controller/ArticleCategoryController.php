<?php

namespace App\Controller;

use App\Entity\ArticleCategory;
use App\Form\ArticleCategoryType;
use App\Service\ArticleCategory\CreateCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/categories', name: 'app_article_category.')]
class ArticleCategoryController extends AbstractController

{
    #[Route('/create', name: 'create')]
    public function index(Request $request, CreateCategory $categoryService): Response
    {
		$formCategory = $this->createForm(ArticleCategoryType::class);
		$formCategory->handleRequest($request);

		if ($formCategory->isSubmitted() && $formCategory->isValid()){
			$categoryService->create($formCategory->getData());
			return $this->redirectToRoute('app_article.create');
		}

        return $this->render('article_category/index.html.twig', [
            'controller_name' => 'ArticleCategoryController',
        ]);
    }
}
