<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/categories', name: 'app_article_category.')]
class ArticleCategoryController extends AbstractController

{
    #[Route('/create', name: 'create')]
    public function index(): Response
    {
        return $this->render('article_category/index.html.twig', [
            'controller_name' => 'ArticleCategoryController',
        ]);
    }
}
