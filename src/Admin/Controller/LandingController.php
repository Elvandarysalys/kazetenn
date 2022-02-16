<?php

namespace Kazetenn\Admin\Controller;

use Kazetenn\Admin\Model\AdminMenu;
use Kazetenn\Admin\Service\PageHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends BaseAdminController
{
    /**
     * @Route("interface", name="admin_index", methods={"GET"}, priority="1")
     */
    public function index(): Response
    {
        return $this->render('@KazetennAdmin/admin_landing.html.twig');
    }

    /**
     * @Route("display_page/{page}", name="admin_page_action", methods={"GET"}, priority="1")
     */
    public function pageAction(PageHandler $pageHandler, string $page = null): Response
    {
        /** @var array $pages */
        $pages = $this->getParameter('kazetenn_admin.' . AdminMenu::PAGES_ENTRIES_NAME);
        if (null !== $page && array_key_exists($page, $pages)) {
            return $this->render('@KazetennAdmin/admin_page.html.twig', [
                'page' => $pageHandler->getPage($page)
            ]);
        } else {
            return $this->render('@KazetennAdmin/admin_page_not_found.html.twig', [
                'page_name' => $page,
                'pages'     => $pages
            ]);
        }
    }
}
