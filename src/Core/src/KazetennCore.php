<?php

namespace Kazetenn\Core;

use Kazetenn\Admin\Model\AdminMenu;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;
use function Symfony\Component\Translation\t;

class KazetennCore extends AbstractBundle
{
    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // activating the timestampable and blameable extension
        $builder->prependExtensionConfig('stof_doctrine_extensions', [
            'orm' => [
                'default' => [
                    'timestampable' => true,
                    'blameable'     => true
                ]
            ]
        ]);

        $admin_config[AdminMenu::MENU_AUTHORIZED_ROLES] = ['ROLE_USER'];

        // tests for horizontal menus
        $admin_config[AdminMenu::MENU_ENTRIES_NAME] = [
            'settings_menu' => [
                AdminMenu::MENU_DISPLAY_NAME => 'kazetenn_admin.top_menu.platform_settings_button',
                AdminMenu::MENU_TYPE         => AdminMenu::LINK_TYPE,
                AdminMenu::MENU_ORDER        => 0,
                AdminMenu::MENU_ORIENTATION  => AdminMenu::ORIENTATION_HORIZONTAL,
            ],
            'account_menu'  => [
                AdminMenu::MENU_DISPLAY_NAME => 'kazetenn_admin.top_menu.account_button',
                AdminMenu::MENU_TYPE         => AdminMenu::LINK_TYPE,
                AdminMenu::MENU_ORDER        => 1,
                AdminMenu::MENU_ORIENTATION  => AdminMenu::ORIENTATION_HORIZONTAL,
            ],
            'logout_menu'   => [
                AdminMenu::MENU_DISPLAY_NAME => 'kazetenn_admin.top_menu.logout_button',
                AdminMenu::MENU_TARGET       => 'kazetenn_users_security_logout',
                AdminMenu::MENU_TYPE         => AdminMenu::ROUTE_TYPE,
                AdminMenu::MENU_ORDER        => 2,
                AdminMenu::MENU_ORIENTATION  => AdminMenu::ORIENTATION_HORIZONTAL,
            ]
        ];

        // general content handling (basic edition)
        t('kazetenn_admin.nav_size.main_menus', [], 'kazetenn_admin');

        $admin_config[AdminMenu::MENU_ENTRIES_NAME]['main_menu'] = [
            AdminMenu::MENU_DISPLAY_NAME => 'kazetenn_admin.nav_size.main_menus',
            AdminMenu::MENU_TYPE         => AdminMenu::HEADER_TYPE,
            AdminMenu::MENU_ORDER        => 0,
            AdminMenu::MENU_CHILDREN     => [
                'content_index'    => [
                    AdminMenu::MENU_TARGET       => 'content_index',
                    AdminMenu::MENU_DISPLAY_NAME => 'admin_menu.contents_link',
                    AdminMenu::MENU_TYPE         => AdminMenu::PAGE_TYPE,
                    AdminMenu::MENU_ORDER        => 0,
                ],
                'content_handling' => [
                    AdminMenu::MENU_TARGET       => 'kazetenn_admin_content_handling',
                    AdminMenu::MENU_DISPLAY_NAME => 'admin_menu.content_handling_link',
                    AdminMenu::MENU_TYPE         => AdminMenu::ROUTE_TYPE,
                    AdminMenu::MENU_ORDER        => 2,
                ]
            ]
        ];

        $admin_config[AdminMenu::PAGES_ENTRIES_NAME]['content_index'] = [
            AdminMenu::PAGE_FUNCTION => 'Kazetenn\Core\Controller\ContentController::contentListAction',
        ];

        // adding the routes to the menu
        $builder->prependExtensionConfig('kazetenn_admin', $admin_config);
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $services = $container->services()
                              ->defaults()
                              ->autowire()
                              ->autoconfigure();

        $services->load('Kazetenn\\Core\\', './*')
                 ->exclude('./{DependencyInjection, Tests}');

        $services->set('Kazetenn\Core\Service\ContentService')
                 ->arg('$availableContentTypes', tagged_iterator('kazetenn.content_type_tag'));
    }
}
