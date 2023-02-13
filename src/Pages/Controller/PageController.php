<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Pages\Controller;

use Kazetenn\Core\Entity\BaseContentInterface;
use Kazetenn\Core\Model\ContentInterface;
use Kazetenn\Core\Model\ContentModel;
use Kazetenn\Pages\Entity\Page;
use Kazetenn\Pages\Entity\PageContent;
use Kazetenn\Pages\Form\PageType;
use Kazetenn\Pages\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends ContentModel implements ContentInterface
{
    public function __construct(protected PageRepository $pageRepository)
    {
    }

    public function getFormType(): string
    {
        return PageType::class;
    }

    public function getNewContent(): BaseContentInterface
    {
        return new Page();
    }

    public function getContentName(): string
    {
        return 'page';
    }

    public function getContentClass(): string
    {
        return Page::class;
    }

    public function getContentTemplate(): string
    {
        return '@KazetennPages/content/page_form.html.twig';
    }

    public function getBlockClass(): string
    {
        return PageContent::class;
    }

    #[Route("/404", name: "page_not_found", methods: ["GET"], priority: 1)]
    public function notFound(): Response
    {
        return $this->render('@KazetennPages/not_found.html.twig');
    }

    #[Route("/{page_path_1}/{page_path_2}", name: "front_index", methods: ["GET"])]
    public function index(PageRepository $pageRepository, string $page_path_1 = null, string $page_path_2 = null): Response
    {
        /** @var Page|null $page */
        $page = $pageRepository->findPage($page_path_1, $page_path_2);

        if (null === $page) {
            return $this->redirectToRoute('kazetenn_page_not_found');
        }

        return $this->render('@KazetennPages/display_page.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * Used as a route in the admin to  list all pages.
     * todo: can this be turned into a custom all content route ?
     * @return string
     */
    public function listAction(): string
    {
        return $this->renderView('@KazetennPages/page_index.html.twig', [
            'pages' => $this->pageRepository->findAll(),
        ]);
    }
}
