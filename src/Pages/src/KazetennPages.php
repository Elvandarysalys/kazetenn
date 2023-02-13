<?php

/*
 * This file is part of the Kazetenn Pages Bundle
 *
 * (c) Gwilherm-Alan Turpin (elvandar.ysalys@protonmail.com) 2022.
 *
 * For more informations about the license and copyright, please view the LICENSE file at the root of the project.
 */

namespace Kazetenn\Pages;

use Kazetenn\Admin\Model\AdminMenu;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use function Symfony\Component\Translation\t;

class KazetennPages extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
                   ->children()
                   ->scalarNode('pages_url')
                   ->defaultValue('')
                   ->end()
                   ->scalarNode('blog_name')
                   ->defaultValue('')
                   ->end()
                   ->end();
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        t('kazetenn_admin.nav_size.page_menus', [], 'kazetenn_admin');

        $admin_config[AdminMenu::MENU_ENTRIES_NAME]['page_menu'] = [
            AdminMenu::MENU_DISPLAY_NAME => 'kazetenn_admin.nav_size.page_menus',
            AdminMenu::MENU_TYPE         => AdminMenu::HEADER_TYPE,
            AdminMenu::MENU_ORDER        => 1,
            AdminMenu::MENU_CHILDREN     => [
                'pages_index'   => [
                    AdminMenu::MENU_TARGET       => 'pages_index',
                    AdminMenu::MENU_DISPLAY_NAME => 'admin_menu.pages_link',
                    AdminMenu::MENU_TYPE         => AdminMenu::PAGE_TYPE,
                    AdminMenu::MENU_ORDER        => 0,
                ],
                'page_handling' => [
                    AdminMenu::MENU_TARGET           => 'kazetenn_admin_content_handling',
                    AdminMenu::MENU_TARGET_ARGUMENTS => ['type' => 'page'],
                    AdminMenu::MENU_DISPLAY_NAME     => 'admin_menu.page_handling_link',
                    AdminMenu::MENU_TYPE             => AdminMenu::ROUTE_TYPE,
                    AdminMenu::MENU_ORDER            => 1,
                ]
            ]
        ];

        $admin_config[AdminMenu::PAGES_ENTRIES_NAME]['pages_index'] = [
            AdminMenu::PAGE_FUNCTION => 'Kazetenn\Pages\Controller\PageController::listAction',
        ];

        $builder->prependExtensionConfig('kazetenn_admin', $admin_config);
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->setParameter('kazetenn_pages.pages_url', $config['pages_url']);
        $builder->setParameter('kazetenn_pages.blog_name', $config['blog_name']);

        $services = $container->services()
                              ->defaults()
                              ->autowire()
                              ->autoconfigure();

        $services->load('Kazetenn\\Pages\\', './*')
                 ->exclude('./{DependencyInjection, Tests}');
    }
}
