<?php

namespace App\Controller;

use App\Service\Article\ArticleFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ArticleFinder $articleFinder): Response
    {
		$articles = $articleFinder->findLastArticles(6);
		foreach ($articles as $article){
			if (strlen($article->getContent()) > 80){
				$article->setContent(substr($article->getContent(),0,80) . '...');
			}
		}

        return $this->render('main/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
