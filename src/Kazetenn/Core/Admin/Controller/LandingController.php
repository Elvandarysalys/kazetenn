<?php

namespace Kazetenn\Core\Admin\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends BaseAdminController
{
    /**
     * @Route("/", name="admin_index", methods={"GET"}, priority="1")
     */
    public function index(): Response
    {
        return $this->render('@KazetennAdmin/admin_landing.html.twig');
    }
}
