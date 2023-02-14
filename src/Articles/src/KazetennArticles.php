<?php

/*
 * This file is part of the Kazetenn Articles Bundle
 *
 * (c) Gwilherm-Alan Turpin (elvandar.ysalys@protonmail.com) 2022.
 *
 * For more informations about the license and copyright, please view the LICENSE file at the root of the project.
 */

namespace Kazetenn\Articles;

use Kazetenn\Admin\Model\AdminMenu;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use function Symfony\Component\Translation\t;

class KazetennArticles extends AbstractBundle
{
    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        t('kazetenn_admin.nav_size.article_menus', [], 'kazetenn_admin');

        $admin_config[AdminMenu::MENU_ENTRIES_NAME]['article_menu'] = [
            AdminMenu::MENU_DISPLAY_NAME => 'kazetenn_admin.nav_size.article_menus',
            AdminMenu::MENU_TYPE         => AdminMenu::HEADER_TYPE,
            AdminMenu::MENU_ORDER        => 3,
            AdminMenu::MENU_CHILDREN     => [
                'articles_index'   => [
                    AdminMenu::MENU_TARGET       => 'articles_index',
                    AdminMenu::MENU_DISPLAY_NAME => 'admin_menu.articles_link',
                    AdminMenu::MENU_TYPE         => AdminMenu::PAGE_TYPE,
                    AdminMenu::MENU_ORDER        => 0,
                ],
                'article_handling' => [
                    AdminMenu::MENU_TARGET           => 'kazetenn_admin_content_handling',
                    AdminMenu::MENU_TARGET_ARGUMENTS => ['type' => 'article'],
                    AdminMenu::MENU_DISPLAY_NAME     => 'admin_menu.article_handling_link',
                    AdminMenu::MENU_TYPE             => AdminMenu::ROUTE_TYPE,
                    AdminMenu::MENU_ORDER            => 1,
                ]
            ]
        ];

        $admin_config[AdminMenu::PAGES_ENTRIES_NAME]['articles_index'] = [
            AdminMenu::PAGE_FUNCTION => 'Kazetenn\articles\Controller\articlesController::listAction',
        ];

        $builder->prependExtensionConfig('kazetenn_admin', $admin_config);
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
                   ->children()
                   ->scalarNode('article_url')
                   ->defaultValue('content')
                   ->end()
                   ->end();
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->setParameter('kazetenn_articles.article_url', $config['article_url']);

        $services = $container->services()
                              ->defaults()
                              ->autowire()
                              ->autoconfigure();

        $services->load('Kazetenn\\Articles\\', './*')
                 ->exclude('./{DependencyInjection, Tests}');
    }
}
