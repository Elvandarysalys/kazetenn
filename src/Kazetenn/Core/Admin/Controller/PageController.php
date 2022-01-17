<?php

namespace Kazetenn\Core\Admin\Controller;

use Kazetenn\Pages\Entity\Page;
use Kazetenn\Pages\Entity\PageContent;
use Kazetenn\Kazetenn\Core\Admin\Form\PageContentType;
use Kazetenn\Kazetenn\Core\Admin\Form\PageType;
use Kazetenn\Pages\Repository\PageContentRepository;
use Kazetenn\Pages\Repository\PageRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use function dump;

/**
 * @Route("/pages")
 */
class PageController extends BaseAdminController
{
    /**
     * @Route("/", name="page_index", methods={"GET"}, priority="1")
     */
    public function index(PageRepository $pageRepository): Response
    {
        return $this->render('@KazetennAdmin/page/index.html.twig', [
            'pages' => $pageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/handling", name="page_handling", methods={"GET","POST"}, priority="1")
     * @Route("/handling/{id}", name="page_handling_edit", methods={"GET","POST"}, priority="1")
     */
    public function createEditPage(Request $request, PageRepository $pageRepository, Page $page = null): Response
    {
        if (null === $page) {
            $page = new Page();
        }
        $form = $this->createForm(PageType::class, $page, ['repository' => $pageRepository]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();

            return $this->redirectToRoute('kazetenn_admin_page_index', [], Response::HTTP_SEE_OTHER);
        }

        $ajaxRoute = $this->generateUrl('kazetenn_admin_ajax_page_handling_edit', ['id' => $page->getId()]);

        return $this->render('@KazetennAdmin/page/page_form.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
            'ajax_route' => $ajaxRoute
        ]);
    }

    /**
     * @Route("/ajax_handling", name="ajax_page_handling", methods={"GET","POST"}, priority="1")
     * @Route("/ajax_handling/{id}", name="ajax_page_handling_edit", methods={"GET","POST"}, priority="1")
     */
    public function ajaxCreateEditPage(Request $request, PageRepository $pageRepository, SerializerInterface $serializer, Page $page = null): Response
    {
        if (null === $page) {
            $page = new Page();
        }
        $form = $this->createForm(PageType::class, $page, ['repository' => $pageRepository]);
        $form->handleRequest($request);
        dump($form);
        return new JsonResponse($serializer->serialize($page, 'JSON'));
    }

    /**
     * @Route("/{id}", name="page_show", methods={"GET"}, priority="1")
     */
    public function preview(Page $page): Response
    {
        return $this->render('@KazetennAdmin/page/preview.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * @Route("/{id}", name="page_delete", methods={"POST"}, priority="1")
     */
    public function delete(Request $request, Page $page): Response
    {
        if ($this->isCsrfTokenValid('delete' . $page->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($page);
            $entityManager->flush();
        }

        return $this->redirectToRoute('kazetenn_admin_page_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/", name="page_content_index", methods={"GET"}, priority="1")
     */
    public function indexContent(PageContentRepository $pageContentRepository): Response
    {
        return $this->render('@KazetennAdmin/page_content/index.html.twig', [
            'page_contents' => $pageContentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="page_content_new", methods={"GET","POST"}, priority="1")
     */
    public function newContent(Page $page, Request $request, PageRepository $pageRepository, PageContentRepository $pageContentRepository): Response
    {
        $pageContent = new PageContent();
        $pageContent->setPage($page);
        $form = $this->createForm(PageContentType::class, $pageContent, ['page_repository' => $pageRepository, 'bloc_repository' => $pageContentRepository]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pageContent);
            $entityManager->flush();

            return $this->redirectToRoute('kazetenn_admin_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('@KazetennAdmin/page_content/new.html.twig', [
            'page_content' => $pageContent,
            'form'         => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="page_content_show", methods={"GET"}, priority="1")
     */
    public function showContent(PageContent $pageContent): Response
    {
        return $this->render('@KazetennAdmin/page_content/show.html.twig', [
            'page_content' => $pageContent,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="page_content_edit", methods={"GET","POST"}, priority="1")
     */
    public function editContent(Request $request, PageContent $pageContent): Response
    {
        $form = $this->createForm(PageContentType::class, $pageContent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('kazetenn_admin_page_content_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('@KazetennAdmin/page_content/edit.html.twig', [
            'page_content' => $pageContent,
            'form'         => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="page_content_delete", methods={"POST"}, priority="1")
     */
    public function deleteContent(Request $request, PageContent $pageContent): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pageContent->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pageContent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('kazetenn_admin_page_content_index', [], Response::HTTP_SEE_OTHER);
    }
}
