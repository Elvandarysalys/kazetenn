<?php

namespace Kazetenn\Core\Admin\Controller;

use Kazetenn\Core\Admin\Service\MenuHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseAdminController extends AbstractController
{
    private MenuHandler $menuHandler;

    /**
     * @param MenuHandler $menuHandler
     */
    public function __construct(MenuHandler $menuHandler)
    {
        $this->menuHandler = $menuHandler;
    }

    public function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $parameters['admin_menu_list'] = $this->menuHandler->buildMenuEntries();
        return parent::render($view, $parameters, $response);
    }
}
