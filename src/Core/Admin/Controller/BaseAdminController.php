<?php

namespace Kazetenn\Core\Admin\Controller;

use Kazetenn\Kazetenn\Core\Admin\Model\AdminMenu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class BaseAdminController extends AbstractController
{
    protected TranslatorInterface $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    private function buildMenuEntries(): array
    {
        $menu_list = [];
        foreach ($this->getParameter('kazetenn.menu_entries') as $data) {
            $routeInfo = $data['destination'];

            if (!filter_var($routeInfo, FILTER_VALIDATE_URL)) {
                try {
                    $url = $this->generateUrl($routeInfo);
                } catch (RouteNotFoundException $e) {
                    continue;
                }
            } else {
                $url = $routeInfo;
            }

            $menu_list[] = new AdminMenu($data['name'], $url, $data['display_name']);
        }
        $menu_list[] = new AdminMenu('pages', $this->generateUrl('kazetenn_admin_page_index'), $this->translator->trans('admin_menu.pages_link', [], 'kazetenn_admin'));

        return $menu_list;
    }

    public function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $parameters['admin_menu_list'] = $this->buildMenuEntries();
        return parent::render($view, $parameters, $response);
    }
}
