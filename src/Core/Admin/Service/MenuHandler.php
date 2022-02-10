<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Admin\Service;

use Kazetenn\Core\Admin\DependencyInjection\Configuration;
use Kazetenn\Core\Admin\Model\AdminMenu;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuHandler
{
    protected TranslatorInterface $translator;
    protected LoggerInterface     $logger;
    private ContainerInterface    $container;
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param ContainerInterface $container
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, ContainerInterface $container, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $this->translator = $translator;
        $this->logger     = $logger;
        $this->container  = $container;
        $this->urlGenerator  = $urlGenerator;
    }

    /**
     * @param string $name
     * @param array<string> $configData
     * @param string $defaultTranslationDomain
     * @return AdminMenu
     */
    private function buildMenu(string $name, array $configData, string $defaultTranslationDomain): AdminMenu
    {
        $routeInfo = $configData[AdminMenu::MENU_TARGET];

        $type = 'link';
        switch ($configData[AdminMenu::MENU_TYPE]) {
            case AdminMenu::LINK_TYPE:
                if (!filter_var($routeInfo, FILTER_VALIDATE_URL)) {
                    trigger_error("The selected url for $name is not valid", E_USER_WARNING);
                    $url = '#';
                } else {
                    $url = $routeInfo;
                }
                break;
            case AdminMenu::PAGE_TYPE:
                /** @var array<string> $pages */
                $pages = $this->container->getParameter('kazetenn_admin.pages');
                if (array_key_exists($configData['target'], $pages)) {
                    $url = $this->urlGenerator->generate('kazetenn_admin_admin_page_action', ['page' => $configData['target']]);
                } else {
                    $this->logger->warning('Page ' . $configData['target'] . ' does not exist.');
                    $url = '#';
                }
                break;
            default:
            case AdminMenu::ROUTE_TYPE:
                try {
                    $url = $this->urlGenerator->generate($routeInfo);
                } catch (RouteNotFoundException $e) {
                    $errorMessage = $e->getMessage();
                    trigger_error("The selected route for $name cannot be generated with error: $errorMessage", E_USER_WARNING);
                    $url = '#';
                }
                break;
            case AdminMenu::HEADER_TYPE:
                $type = 'main';
                $url  = '';
                break;
        }

        $translationDomain = $defaultTranslationDomain;
        if (array_key_exists(AdminMenu::MENU_TRANSLATION_DOMAIN, $configData)) {
            if (!empty($configData[AdminMenu::MENU_TRANSLATION_DOMAIN])) {
                $translationDomain = $configData[AdminMenu::MENU_TRANSLATION_DOMAIN];
            }
        }

        $displayedName = $this->translator->trans($configData['display_name'], [], $translationDomain);

        $adminMenu = new AdminMenu($name, $url, $displayedName, $type);
        if (array_key_exists(AdminMenu::MENU_CHILDREN, $configData)){
            /** @var array $menuChildren */
            $menuChildren = $configData[AdminMenu::MENU_CHILDREN];
            if (!empty($menuChildren)) {
                $children = [];
                foreach ($menuChildren as $name => $data) {
                    if (array_key_exists($data[AdminMenu::MENU_ORDER], $children)) {
                        trigger_error("There is already a menu entry with the same order as $name.", E_USER_WARNING);
                    }
                    $children[$data[AdminMenu::MENU_ORDER]] = $this->buildMenu($name, $data, $defaultTranslationDomain);
                }
                $adminMenu->setChildren($children);
            }
        }

        return $adminMenu;
    }

    public function buildMenuEntries(): array
    {
        $menu_list                = [];
        /** @var string $defaultTranslationDomain */
        $defaultTranslationDomain = $this->container->getParameter('kazetenn_admin.translation_domain');
        if (empty($defaultTranslationDomain)) {
            $defaultTranslationDomain = AdminMenu::DEFAULT_TRANSLATION_DOMAIN;
        }

        /** @var array $menu_entries */
        $menu_entries = $this->container->getParameter('kazetenn_admin.' . AdminMenu::MENU_ENTRIES_NAME);
        foreach ($menu_entries as $name => $data) {dump($data);
            if (array_key_exists($data[AdminMenu::MENU_ORDER], $menu_list)) {
                trigger_error("There is already a menu entry with the same order as $name.", E_USER_WARNING);
            }
            $menu_list[$data[AdminMenu::MENU_ORDER]] = $this->buildMenu($name, $data, $defaultTranslationDomain);
        }

        return $menu_list;
    }
}
