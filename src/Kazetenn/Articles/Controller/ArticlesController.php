<?php
/*
 * This file is part of the Kazetenn Articles Bundle
 *
 * (c) Gwilherm-Alan Turpin (elvandar.ysalys@protonmail.com) 2022.
 *
 * For more informations about the license and copyright, please view the LICENSE file at the root of the project.
 */

namespace Kazetenn\Articles\Controller;

use Exception;
use Kazetenn\Articles\Entity\Article;
use Kazetenn\Articles\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/{article_path_1}/{article_path_2}", name="front_index", methods={"GET"})
     * @throws Exception
     */
    public function index(ArticleRepository $articleRepository, string $article_path_1 = null, string $article_path_2 = null): Response
    {
        /** @var Article|null $article */
        $article = $articleRepository->findPage($article_path_1, $article_path_2);

        if (null === $article) {
            return $this->redirectToRoute('not_found');
        }

        return $this->render('@KazetennArticles/display_page.html.twig', [
            'content' => $article,
        ]);
    }

    /**
     * @Route("/404", name="not_found", methods={"GET"}, priority="1")
     */
    public function notFound(): Response
    {
        return $this->render('@KazetennArticles/not_found.html.twig');
    }
}
