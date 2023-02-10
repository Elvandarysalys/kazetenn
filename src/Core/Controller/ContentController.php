<?php

namespace Kazetenn\Core\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Kazetenn\Admin\Controller\BaseAdminController;
use Kazetenn\Admin\Service\MenuHandler;
use Kazetenn\Core\Entity\BaseContentInterface;
use Kazetenn\Core\Form\ContentType;
use Kazetenn\Core\Service\ContentService;
use Kazetenn\Pages\Entity\Page;
use Kazetenn\Pages\Entity\PageContent;
use Kazetenn\Pages\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/pages")]
class ContentController extends BaseAdminController
{
    public function __construct(MenuHandler $menuHandler, protected PageRepository $pageRepository, protected ContentService $contentService)
    {
        parent::__construct($menuHandler);
    }

    /**
     * Used as a route in the admin to  list all pages.
     * todo: can this be turned into a custom all content route ?
     * @return string
     */
    public function contentListAction(): string
    {
        return $this->renderView('@Core/content_handling/page_index.html.twig', [
            'pages' => $this->pageRepository->findAll(),
        ]);
    }

    /**
     * Used to create or edit any content based on the tagged_iterator
     */
    #[Route("/handling_content/{content}", name: "content_handling", methods: ["GET", "POST"], priority: 1)]
    public function createEditContent(Request $request, ManagerRegistry $managerRegistry, ?BaseContentInterface $content = null): Response
    {
        $creation = true;
        $formType = ContentType::class;

        $contentType = null;
        if (null !== $content) {
            $contentType = $this->contentService->getContentByClass($content);
            if (null !== $contentType) {
                $formType = $contentType->getFormType();
            }
        }

        /**
         * At this point, we know if this is
         * - a creation of a new content
         * - an edit of an existing content
         *
         * We also determined the form type.
         */
        $form = $this->createForm($formType, $content, ['creation' => $creation]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // if there was no specified content, this means it is a creation, and we need to get the data from the form and build the appropriate one
            if (null === $content) {
                $type = $form->get('content_type')->getData();

                $contentType = $this->contentService->getContentByName($type);
                if (null === $contentType) {
                    throw new Exception('Unknown content type');
                }

                // In the case of a creation, the form IS NOT mapped, therefore we must map information ourselves.
                $content = $contentType->getNewContent();
                $content->setTitle($data['title']);
                $content->setSlug($data['slug']);
            }

            /**
             * If this is a creation, this will allow to create the bare minimum of a content and move onto the more precise edit.
             *
             * If this is an edition, the form should be mapped and therefore the $content will be updated and be saved.
             */
            $managerRegistry->getManager()->persist($content);
            $managerRegistry->getManager()->flush();

            return $this->redirectToRoute('kazetenn_admin_content_handling', ['content' => $content->getId()->toRfc4122()]);
        }

        $template = '@Core/content_handling/page_form.html.twig';
        if (null !== $contentType) {
            $template = $contentType->getContentTemplate();
        }
        return $this->render($template, [
            'form' => $form->createView()
        ]);
    }

    #[Route("/content/preview/{id}", name: "page_show", methods: ["GET"], priority: 1)]
    public function preview(Page $page): Response
    {
        return $this->render('@Core/content_handling/preview.html.twig', [
            'page' => $page,
        ]);
    }

    #[Route('/add_content/{page}/{higherOrder}/{parent}', name: 'page_add_content')]
    public function addContent(ManagerRegistry $managerRegistry, Page $page, PageContent $parent = null, int $higherOrder = 0): Response
    {
        $pageContent = new PageContent();
        $pageContent->setBaseContent($page);
        $pageContent->setParent($parent);
        $pageContent->setBlocOrder($higherOrder + 1);

        $managerRegistry->getManager()->persist($pageContent);
        $managerRegistry->getManager()->flush();

        return $this->redirectToRoute('kazetenn_admin_content_handling', ['content' => $page->getId()->toRfc4122()]);
    }
}
