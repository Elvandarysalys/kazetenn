<?php

namespace Kazetenn\Core\ContentBuilder\DependencyInjection;

use Exception;
use Kazetenn\Core\Admin\Model\AdminMenu;
use Kazetenn\Pages\KazetennPages;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ContentBuilderExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $this->addAnnotatedClassesToCompile([
            '**\\Controller\\',
            '**\\Entity\\',
        ]);
    }

    public function prepend(ContainerBuilder $container)
    {
        // activating the timestampable extension
        $container->prependExtensionConfig('stof_doctrine_extensions', ['orm' => [
            'default' => [
                'timestampable' => true
            ]
        ]]);

        $admin_config[AdminMenu::MENU_ENTRIES_NAME]['main_menu'] = [
            AdminMenu::MENU_DISPLAY_NAME       => 'kazetenn_admin.nav_size.main_menus',
            AdminMenu::MENU_TYPE               => AdminMenu::HEADER_TYPE,
            AdminMenu::MENU_ORDER              => 0,
        ];

        if (in_array(KazetennPages::class, $container->getParameter('kernel.bundles'))) {
            $admin_config[AdminMenu::MENU_ENTRIES_NAME]['main_menu'][AdminMenu::MENU_CHILDREN]['pages'] = [
                AdminMenu::MENU_TARGET       => 'kazetenn_admin_page_index',
                AdminMenu::MENU_DISPLAY_NAME => 'admin_menu.pages_link',
                AdminMenu::MENU_TYPE         => AdminMenu::ROUTE_TYPE,
                AdminMenu::MENU_ORDER        => 0,
            ];
            $admin_config[AdminMenu::MENU_ENTRIES_NAME]['main_menu'][AdminMenu::MENU_CHILDREN]['page_handling'] = [
                AdminMenu::MENU_TARGET       => 'kazetenn_admin_page_handling',
                AdminMenu::MENU_DISPLAY_NAME => 'admin_menu.page_handling_link',
                AdminMenu::MENU_TYPE         => AdminMenu::ROUTE_TYPE,
                AdminMenu::MENU_ORDER        => 1,
            ];

            $admin_config[AdminMenu::MENU_ENTRIES_NAME]['main_menu'][AdminMenu::MENU_CHILDREN]['page_test'] = [
                AdminMenu::MENU_TARGET       => 'pages_index',
                AdminMenu::MENU_DISPLAY_NAME => 'admin_menu.page_test_link',
                AdminMenu::MENU_TYPE         => AdminMenu::PAGE_TYPE,
                AdminMenu::MENU_ORDER        => 2,
            ];

            $admin_config[AdminMenu::PAGES_ENTRIES_NAME]['pages_index'] = [
                AdminMenu::PAGE_FUNCTION => 'Kazetenn\Core\ContentBuilder\Controller\PageController::listAction',
            ];
        }

        // adding the routes to the menu
        $container->prependExtensionConfig('kazetenn_admin', $admin_config);
    }
}
