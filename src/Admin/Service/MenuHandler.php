<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Admin\Service;

use Kazetenn\Admin\Model\AdminMenu;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
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
        $this->translator   = $translator;
        $this->logger       = $logger;
        $this->container    = $container;
        $this->urlGenerator = $urlGenerator;
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
                    $this->logger->warning("The selected url for $name is not valid");
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
                    $this->logger->warning("The selected route for $name cannot be generated with error: $errorMessage");
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
        if (array_key_exists(AdminMenu::MENU_CHILDREN, $configData)) {
            /** @var array $menuChildren */
            $menuChildren = $configData[AdminMenu::MENU_CHILDREN];
            if (!empty($menuChildren)) {
                $children = [];
                foreach ($menuChildren as $name => $data) {
                    if (array_key_exists($data[AdminMenu::MENU_ORDER], $children)) {
                        $this->logger->warning("There is already a menu entry with the same order as $name.");
                    }
                    $children[$data[AdminMenu::MENU_ORDER]] = $this->buildMenu($name, $data, $defaultTranslationDomain);
                }
                $adminMenu->setChildren($children);
            }
        }

        return $adminMenu;
    }

    public function buildMenuEntries(?UserInterface $user): array
    {
        $menu_list = [];
        /** @var string $defaultTranslationDomain */
        $defaultTranslationDomain = $this->container->getParameter('kazetenn_admin.translation_domain');
        if (empty($defaultTranslationDomain)) {
            $defaultTranslationDomain = AdminMenu::DEFAULT_TRANSLATION_DOMAIN;
        }

        /** @var array $authorizedRoles */
        $authorizedRoles = $this->container->getParameter('kazetenn_admin.authorized_roles');
        if (!$this->isAuthorized($user, $authorizedRoles)) {
            return [];
        }

        /** @var array $menu_entries */
        $menu_entries = $this->container->getParameter('kazetenn_admin.' . AdminMenu::MENU_ENTRIES_NAME);
        foreach ($menu_entries as $name => $data) {
            if (array_key_exists($data[AdminMenu::MENU_ORDER], $menu_list)) {
                $this->logger->warning("There is already a menu entry with the same order as $name.");
            }

            if ($this->isAuthorized($user, $data[AdminMenu::MENU_AUTHORIZED_ROLES], true)) {
                $menu_list[$data[AdminMenu::MENU_ORIENTATION]][$data[AdminMenu::MENU_ORDER]] = $this->buildMenu($name, $data, $defaultTranslationDomain);
            }
        }

        return $menu_list;
    }

    public function isAuthorizedToView(?UserInterface $user, bool $return = true): bool
    {
        /** @var array $authorizedRoles */
        $authorizedRoles = $this->container->getParameter('kazetenn_admin.authorized_roles');

        return $this->isAuthorized($user, $authorizedRoles);
    }

    private function isAuthorized(?UserInterface $user, array $authorizedRoles, bool $emptyIsValid = false): bool
    {
        if ($emptyIsValid && empty($authorizedRoles)) { // avoid going into the other loops.
            return true;
        } else {
            $anonymous = in_array(AdminMenu::ANONYMOUS_MENU, $authorizedRoles);
            if (!$anonymous) {
                if (null === $user) { // if there is no user and anonymous is false, return false
                    return false;
                } else {
                    // if the arrays intersect (not empty) the user has the one of required roles
                    $intersect = array_intersect($user->getRoles(), $authorizedRoles);
                    // if both count are equals, then the user has all the required roles
                    return !empty($intersect) && count($intersect) === count($authorizedRoles);
                }
            } else {
                return true; // if anonymous is granted, the function will always return true.
            }
        }
    }
}
