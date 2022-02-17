<?php

namespace Kazetenn\Admin\Controller;

use Kazetenn\Admin\Model\AdminMenu;
use Kazetenn\Admin\Service\MenuHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseAdminController extends AbstractController
{
    protected MenuHandler $menuHandler;

    /**
     * @param MenuHandler $menuHandler
     */
    public function __construct(MenuHandler $menuHandler)
    {
        $this->menuHandler = $menuHandler;
    }

    public function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $user = $this->getUser();
        if ($this->menuHandler->isAuthorizedToView($user)) {
            $menu_list                                     = $this->menuHandler->buildMenuEntries($user);
            $parameters[AdminMenu::ORIENTATION_HORIZONTAL] = $menu_list[AdminMenu::ORIENTATION_HORIZONTAL];
            $parameters[AdminMenu::ORIENTATION_VERTICAL]   = $menu_list[AdminMenu::ORIENTATION_VERTICAL];
            return parent::render($view, $parameters, $response);
        }
        throw $this->createAccessDeniedException();
    }
}
