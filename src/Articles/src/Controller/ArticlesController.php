<?php
/*
 * This file is part of the Kazetenn Articles Bundle
 *
 * (c) Gwilherm-Alan Turpin (elvandar.ysalys@protonmail.com) 2022.
 *
 * For more informations about the license and copyright, please view the LICENSE file at the root of the project.
 */

namespace Kazetenn\Articles\Controller;

use Kazetenn\Articles\Entity\Article;
use Kazetenn\Articles\Entity\ArticleContent;
use Kazetenn\Articles\Form\ArticleType;
use Kazetenn\Articles\Repository\ArticleRepository;
use Kazetenn\Core\Entity\BaseContentInterface;
use Kazetenn\Core\Model\ContentInterface;
use Kazetenn\Core\Model\ContentModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends ContentModel implements ContentInterface
{
    public function __construct(protected ArticleRepository $pageRepository)
    {
    }

    public function getFormType(): string
    {
        return ArticleType::class;
    }

    public function getNewContent(): BaseContentInterface
    {
        return new Article();
    }

    public function getContentName(): string
    {
        return 'article';
    }

    public function getContentClass(): string
    {
        return Article::class;
    }

    public function getContentTemplate(): string
    {
        return '@KazetennArticles/content/page_form.html.twig';
    }

    public function getBlockClass(): string
    {
        return ArticleContent::class;
    }

    #[Route("/{article_path_1}/{article_path_2}", name: "front_index", methods: ["GET"])]
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

    #[Route("/404", name: "not_found", methods: ["GET"], priority: 1)]
    public function notFound(): Response
    {
        return $this->render('@KazetennArticles/not_found.html.twig');
    }
}
