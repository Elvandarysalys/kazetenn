<?php

namespace Kazetenn\Core\Admin\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Kazetenn\Core\Admin\Model\FormModel;
use Kazetenn\Core\Admin\Form\PageContentType;
use Kazetenn\Core\Admin\Form\PageType;
use Kazetenn\Pages\Entity\Page;
use Kazetenn\Pages\Entity\PageContent;
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
     * @Route("/handling/{id<\S+>?}", name="page_handling", methods={"GET","POST"}, priority="1")
     */
    public function createEditPage(Page $page = null): Response
    {
        if (null === $page) {
            $pageId = null;
        } else {
            $pageId = $page->getId()->toRfc4122();
        }

        $ajaxRoute = $this->generateUrl('kazetenn_admin_ajax_page_handling', ['id' => $pageId]);

        return $this->render('@KazetennAdmin/page/page_form.html.twig', [
            'ajax_route'       => $ajaxRoute,
            'page_id'          => $pageId,
            'ajax_add_content' => $this->generateUrl('kazetenn_admin_ajax_page_add_content', ['id' => null])
        ]);
    }

    /**
     * @Route("/ajax_handling/{id<\S+>?}", name="ajax_page_handling", methods={"GET","POST"}, priority="1")
     * @throws Exception
     */
    public function ajaxCreateEditPage(Request $request, PageRepository $pageRepository, ManagerRegistry $managerRegistry, SerializerInterface $serializer, Page $page = null): Response
    {
        if (null === $page) {
            $page   = new Page();
            $pageId = null;
        } else {
            $pageId = $page->getId();
        }

        $form = $this->createForm(PageType::class, $page, ['repository' => $pageRepository]);

        if ($request->getMethod() === 'POST') {
            $data = json_decode($request->getContent(), true);
            $form->submit($data);
        }

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $managerRegistry->getManager()->persist($page);
                $managerRegistry->getManager()->flush();
                $pageId = $page->getId()->toRfc4122();
            } else {
                dump($form->getErrors());
            }
        }

        $ajaxRoute = $this->generateUrl('kazetenn_admin_ajax_page_handling', ['id' => $pageId]);

        $formModel = new FormModel($form->createView());
dump($formModel->getFormDataArray());
        return new JsonResponse($serializer->serialize([
            'data'       => $formModel->getFormDataArray(),
            'ajax_route' => $ajaxRoute
        ],
            'json'));
    }

    /**
     * @Route("/ajax_add_content/{id<\S+>?}", name="ajax_page_add_content", methods={"GET","POST"}, priority="1")
     * @throws Exception
     */
    public function ajaxAddContentToPage(Request $request, PageRepository $pageRepository, ManagerRegistry $managerRegistry, SerializerInterface $serializer, PageContent $pageContent = null): Response
    {
        if (null === $pageContent) {
            $pageContent   = new PageContent();
            $pageContentId = null;
        } else {
            $pageContentId = $pageContent->getId();
        }

        $form = $this->createForm(PageContentType::class, $pageContent, ['repository' => $pageRepository]);

        if ($request->getMethod() === 'POST') {
            $data = json_decode($request->getContent(), true);
            $form->submit($data);
            dump($form);
        }

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager = $managerRegistry->getManager();
                $entityManager->persist($pageContent);
                $entityManager->flush();
                $pageContentId = $pageContent->getId()->toRfc4122();
            } else {
                dump($form->getErrors());
            }
        }

        $ajaxRoute = $this->generateUrl('kazetenn_admin_ajax_page_add_content', ['id' => $pageContentId]);

        $formModel = new FormModel($form->createView());
        return new JsonResponse($serializer->serialize([
            'data'       => $formModel->getFormDataArray(),
            'ajax_route' => $ajaxRoute
        ],'json'));
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
    public function delete(Request $request, Page $page, ManagerRegistry $managerRegistry): Response
    {
        if ($this->isCsrfTokenValid('delete' . $page->getId(), $request->request->get('_token'))) {
            $entityManager = $managerRegistry->getManager();
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
    public function newContent(Page $page, Request $request, PageRepository $pageRepository, PageContentRepository $pageContentRepository, ManagerRegistry $managerRegistry): Response
    {
        $pageContent = new PageContent();
        $pageContent->setPage($page);
        $form = $this->createForm(PageContentType::class, $pageContent, ['page_repository' => $pageRepository, 'bloc_repository' => $pageContentRepository]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $managerRegistry->getManager();
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
    public function editContent(Request $request, PageContent $pageContent, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->createForm(PageContentType::class, $pageContent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $managerRegistry->getManager()->flush();

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
    public function deleteContent(Request $request, PageContent $pageContent, ManagerRegistry $managerRegistry): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pageContent->getId(), $request->request->get('_token'))) {
            $entityManager = $managerRegistry->getManager();
            $entityManager->remove($pageContent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('kazetenn_admin_page_content_index', [], Response::HTTP_SEE_OTHER);
    }
}
