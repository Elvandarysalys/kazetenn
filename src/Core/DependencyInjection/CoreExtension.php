<?php

namespace Kazetenn\Core\DependencyInjection;

use Exception;
use Kazetenn\Admin\Model\AdminMenu;
use Kazetenn\Pages\KazetennPages;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use function Symfony\Component\Translation\t;

class CoreExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $this->addAnnotatedClassesToCompile([
            '**\\Controller\\',
            '**\\Entity\\',
        ]);
    }

    public function prepend(ContainerBuilder $container): void
    {
        // activating the timestampable and blameable extension
        $container->prependExtensionConfig('stof_doctrine_extensions', ['orm' => [
            'default' => [
                'timestampable' => true,
                'blameable'     => true
            ]
        ]]);

        $admin_config[AdminMenu::MENU_AUTHORIZED_ROLES]          = ['ANONYMOUS'];
        $admin_config[AdminMenu::MENU_ENTRIES_NAME]['main_menu'] = [
            AdminMenu::MENU_DISPLAY_NAME => 'kazetenn_admin.nav_size.main_menus',
            AdminMenu::MENU_TYPE         => AdminMenu::HEADER_TYPE,
            AdminMenu::MENU_ORDER        => 0,
        ];
        t('kazetenn_admin.nav_size.main_menus', [], 'kazetenn_admin');

        // tests for horizontal menus
        $admin_config[AdminMenu::MENU_ENTRIES_NAME]['settings_menu'] = [
            AdminMenu::MENU_DISPLAY_NAME => 'kazetenn_admin.top_menu.platform_settings_button',
            AdminMenu::MENU_TYPE         => AdminMenu::LINK_TYPE,
            AdminMenu::MENU_ORDER        => 0,
            AdminMenu::MENU_ORIENTATION  => AdminMenu::ORIENTATION_HORIZONTAL,
        ];
        $admin_config[AdminMenu::MENU_ENTRIES_NAME]['account_menu']  = [
            AdminMenu::MENU_DISPLAY_NAME => 'kazetenn_admin.top_menu.account_button',
            AdminMenu::MENU_TYPE         => AdminMenu::LINK_TYPE,
            AdminMenu::MENU_ORDER        => 1,
            AdminMenu::MENU_ORIENTATION  => AdminMenu::ORIENTATION_HORIZONTAL,
        ];
        $admin_config[AdminMenu::MENU_ENTRIES_NAME]['logout_menu']   = [
            AdminMenu::MENU_DISPLAY_NAME => 'kazetenn_admin.top_menu.logout_button',
            AdminMenu::MENU_TYPE         => AdminMenu::LINK_TYPE,
            AdminMenu::MENU_ORDER        => 2,
            AdminMenu::MENU_ORIENTATION  => AdminMenu::ORIENTATION_HORIZONTAL,
        ];

        // general content handling (basic edition)
        $admin_config[AdminMenu::MENU_ENTRIES_NAME]['main_menu'][AdminMenu::MENU_CHILDREN]['content_index'] = [
            AdminMenu::MENU_TARGET       => 'content_index',
            AdminMenu::MENU_DISPLAY_NAME => 'admin_menu.contents_link',
            AdminMenu::MENU_TYPE         => AdminMenu::PAGE_TYPE,
            AdminMenu::MENU_ORDER        => 0,
        ];

        $admin_config[AdminMenu::MENU_ENTRIES_NAME]['main_menu'][AdminMenu::MENU_CHILDREN]['content_handling'] = [
            AdminMenu::MENU_TARGET       => 'kazetenn_admin_content_handling',
            AdminMenu::MENU_DISPLAY_NAME => 'admin_menu.content_handling_link',
            AdminMenu::MENU_TYPE         => AdminMenu::ROUTE_TYPE,
            AdminMenu::MENU_ORDER        => 2,
        ];

        $admin_config[AdminMenu::PAGES_ENTRIES_NAME]['content_index'] = [
            AdminMenu::PAGE_FUNCTION => 'Kazetenn\Core\Controller\ContentController::contentListAction',
        ];

        // adding the routes to the menu
        $container->prependExtensionConfig('kazetenn_admin', $admin_config);
    }
}
