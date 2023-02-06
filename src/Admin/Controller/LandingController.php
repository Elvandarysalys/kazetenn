<?php

namespace Kazetenn\Admin\Controller;

use Kazetenn\Admin\Service\PageHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends BaseAdminController
{
    #[Route('interface', name: "admin_index", methods: ["GET"], priority: 1)]
    public function index(): Response
    {
        $this->checkAuthorisation();
        return $this->render('@KazetennAdmin/admin_landing.html.twig');
    }

    #[Route('display_page/{page}', name: "admin_page_action", methods: ["GET"], priority: 1)]
    public function pageAction(PageHandler $pageHandler, ?string $page = null): Response
    {
        $existingPages = $this->menuHandler->getAdminPages();
        if (null !== $page && array_key_exists($page, $existingPages) && $this->checkAuthorisation()) {
            return $this->render('@KazetennAdmin/admin_page.html.twig', [
                'page' => $pageHandler->getPage($page)
            ]);
        }
        return $this->redirectToRoute('kazetenn_admin_page_not_found_action', ['page' => $page]);
    }

    #[Route('page_not_found/{page}', name: 'page_not_found_action', methods: ['GET'])]
    public function pageNotFoundAction(?string $page = null): Response
    {
        return $this->render('@KazetennAdmin/admin_page_not_found.html.twig', [
            'page_name' => $page,
            'pages'     => $this->menuHandler->getAdminPages()
        ]);
    }
}
