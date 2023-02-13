<?php
/*
 * This file is part of the Kazetenn Pages Bundle
 *
 * (c) Gwilherm-Alan Turpin (elvandar.ysalys@protonmail.com) 2022.
 *
 * For more informations about the license and copyright, please view the LICENSE file at the root of the project.
 */

namespace Kazetenn\Pages\DependencyInjection;

use Exception;
use Kazetenn\Admin\Model\AdminMenu;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use function Symfony\Component\Translation\t;

class KazetennPagesExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $container->setParameter('kazetenn_pages.pages_url', $config['pages_url']);
        $container->setParameter('kazetenn_pages.blog_name', $config['blog_name']);

        $this->addAnnotatedClassesToCompile([
            '**\\Controller\\',
            '**\\Entity\\',
        ]);
    }

    public function prepend(ContainerBuilder $container): void
    {
        $admin_config[AdminMenu::MENU_ENTRIES_NAME]['page_menu'] = [
            AdminMenu::MENU_DISPLAY_NAME => 'kazetenn_admin.nav_size.page_menus',
            AdminMenu::MENU_TYPE         => AdminMenu::HEADER_TYPE,
            AdminMenu::MENU_ORDER        => 1,
        ];
        t('kazetenn_admin.nav_size.page_menus', [], 'kazetenn_admin');

        $admin_config[AdminMenu::MENU_ENTRIES_NAME]['page_menu'][AdminMenu::MENU_CHILDREN]['pages_index'] = [
            AdminMenu::MENU_TARGET       => 'pages_index',
            AdminMenu::MENU_DISPLAY_NAME => 'admin_menu.pages_link',
            AdminMenu::MENU_TYPE         => AdminMenu::PAGE_TYPE,
            AdminMenu::MENU_ORDER        => 0,
        ];

        $admin_config[AdminMenu::MENU_ENTRIES_NAME]['page_menu'][AdminMenu::MENU_CHILDREN]['page_handling'] = [
            AdminMenu::MENU_TARGET           => 'kazetenn_admin_content_handling',
            AdminMenu::MENU_TARGET_ARGUMENTS => ['type' => 'page'],
            AdminMenu::MENU_DISPLAY_NAME     => 'admin_menu.page_handling_link',
            AdminMenu::MENU_TYPE             => AdminMenu::ROUTE_TYPE,
            AdminMenu::MENU_ORDER            => 1,
        ];

        $admin_config[AdminMenu::PAGES_ENTRIES_NAME]['pages_index'] = [
            AdminMenu::PAGE_FUNCTION => 'Kazetenn\Pages\Controller\PageController::listAction',
        ];

        $container->prependExtensionConfig('kazetenn_admin', $admin_config);
    }
}
