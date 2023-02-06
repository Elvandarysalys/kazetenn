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
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuHandler
{
    protected TranslatorInterface $translator;
    protected LoggerInterface     $logger;
    private UrlGeneratorInterface $urlGenerator;

    /** @var array<string> $pages */
    private array  $adminPages;
    private string $defaultTranslationDomain;
    private array  $authorizedRoles;
    private array  $menuEntries;

    public function __construct(array $adminPages, string $defaultTranslationDomain, array $authorizedRoles, array $menuEntries, UrlGeneratorInterface $urlGenerator, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $this->adminPages               = $adminPages;
        $this->defaultTranslationDomain = $defaultTranslationDomain;
        $this->menuEntries              = $menuEntries;
        $this->authorizedRoles          = $authorizedRoles;
        $this->translator               = $translator;
        $this->logger                   = $logger;
        $this->urlGenerator             = $urlGenerator;
    }

    public function getAdminPages(): array
    {
        return $this->adminPages;
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
                $pages = $this->adminPages;
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
        $menu_list                = [];
        $defaultTranslationDomain = $this->defaultTranslationDomain;
        if (empty($defaultTranslationDomain)) {
            $defaultTranslationDomain = AdminMenu::DEFAULT_TRANSLATION_DOMAIN;
        }

        if (!$this->isAuthorized($user, $this->authorizedRoles)) {
            return [];
        }

        foreach ($this->menuEntries as $name => $data) {
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
        return $this->isAuthorized($user, $this->authorizedRoles);
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
