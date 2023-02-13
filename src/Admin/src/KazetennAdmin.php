<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kazetenn\Admin;

use Kazetenn\Admin\Model\AdminMenu;
use Kazetenn\Admin\Service\MenuHandler;
use Kazetenn\Admin\Service\PageHandler;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeParentInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class KazetennAdmin extends AbstractBundle
{
    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {

    }

    /**
     * @return ArrayNodeDefinition|NodeBuilder|NodeDefinition|NodeParentInterface|VariableNodeDefinition|null
     */
    private function addMenuEntriesNode()
    {
        $treeBuilder = new TreeBuilder('menu_entries', 'array');

        return $treeBuilder->getRootNode()
                           ->info('Define every entry in the administration menu.')
                           ->useAttributeAsKey(AdminMenu::MENU_NAME)
                           ->arrayPrototype()
                           ->children()
                           ->scalarNode(AdminMenu::MENU_TARGET)
                           ->defaultValue('')
                           ->info('Define the target for the menu entry.')
                           ->end()
                           ->arrayNode(AdminMenu::MENU_TARGET_ARGUMENTS)
                           ->scalarPrototype()->end()
                           ->info('Define details for the target for the menu entry, for example route parameters.')
                           ->end()
                           ->scalarNode(AdminMenu::MENU_DISPLAY_NAME)
                           ->defaultValue('')
                           ->info('Define the displayed name of the menu entry. This value will be translated.')
                           ->end()
                           ->scalarNode(AdminMenu::MENU_TRANSLATION_DOMAIN)
                           ->defaultValue('')
                           ->info('Define the translation domain for this specific menu entry. If not set, the default one set for the bundle will be used.')
                           ->end()
                           ->integerNode(AdminMenu::MENU_ORDER)
                           ->defaultValue(0)
                           ->info('Define the display order of the menu.')
                           ->isRequired()
                           ->end()
                           ->scalarNode(AdminMenu::MENU_TYPE)
                           ->defaultValue('route')
                           ->info('Defines the menu type between link, page , header or route.')
                           ->validate()
                           ->ifNotInArray(AdminMenu::MAIN_MENU_TYPES)
                           ->thenInvalid('Invalid menu type %s . Value should ne link, page, header or route.')
                           ->end()
                           ->end()
                           ->arrayNode('authorized_roles')
                           ->scalarPrototype()->end()
                           ->info('Define the roles who can see the menu')
                           ->end()
                           ->enumNode(AdminMenu::MENU_ORIENTATION)
                           ->values([AdminMenu::ORIENTATION_VERTICAL, AdminMenu::ORIENTATION_HORIZONTAL])
                           ->defaultValue(AdminMenu::ORIENTATION_VERTICAL)
                           ->end()
                           ->arrayNode(AdminMenu::MENU_CHILDREN)
                           ->useAttributeAsKey(AdminMenu::MENU_NAME)
                           ->arrayPrototype()
                           ->children()
                           ->scalarNode(AdminMenu::MENU_TARGET)
                           ->defaultValue('')
                           ->info('Define the target for the menu entry.')
                           ->end()
                           ->arrayNode(AdminMenu::MENU_TARGET_ARGUMENTS)
                           ->scalarPrototype()->end()
                           ->info('Define details for the target for the menu entry, for example route parameters.')
                           ->end()
                           ->integerNode(AdminMenu::MENU_ORDER)
                           ->defaultValue(0)
                           ->info('Define the display order of the menu.')
                           ->isRequired()
                           ->end()
                           ->scalarNode(AdminMenu::MENU_DISPLAY_NAME)
                           ->defaultValue('')
                           ->info('Define the displayed name of the menu entry. This value will be translated.')
                           ->end()
                           ->scalarNode(AdminMenu::MENU_TRANSLATION_DOMAIN)
                           ->defaultValue('')
                           ->info('Define the translation domain for this specific menu entry. If not set, the default one set for the bundle will be used.')
                           ->end()
                           ->arrayNode('authorized_roles')
                           ->scalarPrototype()->end()
                           ->info('Define the roles who can see the menu')
                           ->end()
                           ->scalarNode(AdminMenu::MENU_TYPE)
                           ->defaultValue('route')
                           ->info('Defines the menu type between link, page , or route.')
                           ->validate()
                           ->ifNotInArray(AdminMenu::SUB_MENU_TYPES)
                           ->thenInvalid('Invalid menu type %s . Value should ne link, page or route.')
                           ->end()
                           ->end()
                           ->end()
                           ->end()
                           ->end()
                           ->end()
                           ->end();
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
                   ->children()
                   ->append($this->addMenuEntriesNode())
                   ->scalarNode('admin_url')
                   ->defaultValue('admin')
                   ->info('Define the administration url prefix. By default it is set to /admin/')
                   ->end()
                   ->scalarNode(AdminMenu::MENU_TRANSLATION_DOMAIN)
                   ->defaultValue(AdminMenu::DEFAULT_TRANSLATION_DOMAIN)
                   ->info('Define the default translation domain for the entire bundle')
                   ->end()
                   ->arrayNode('pages')
                   ->useAttributeAsKey(AdminMenu::PAGE_NAME)
                   ->arrayPrototype()
                   ->children()
                   ->scalarNode(AdminMenu::PAGE_FUNCTION)
                   ->end()
                   ->end()
                   ->end()
                   ->end()
                   ->arrayNode('authorized_roles')
                   ->scalarPrototype()->end()
                   ->isRequired()
                   ->info('Define the roles who can see the menu')
                   ->end()
                   ->end();
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        foreach ($config as $key => $item) {
            if ($key === AdminMenu::PAGES_ENTRIES_NAME) {
                foreach ($item as $pageName => $pageData) {
                    $builder->setParameter("$this->extensionAlias.pages.$pageName", $pageData);
                }
            }
            $builder->setParameter($this->extensionAlias . '.' . $key, $item);
        }

        $services = $container->services()
                              ->defaults()
                              ->autowire()
                              ->autoconfigure();

        $services->load('Kazetenn\\Admin\\', './*')
                 ->exclude('./{DependencyInjection, Tests}');

        $services->set(MenuHandler::class)
                 ->arg('$adminPages', '%kazetenn_admin.pages%')
                 ->arg('$authorizedRoles', '%kazetenn_admin.authorized_roles%')
                 ->arg('$defaultTranslationDomain', '%kazetenn_admin.translation_domain%')
                 ->arg('$menuEntries', '%kazetenn_admin.menu_entries%');

        $services->set(PageHandler::class)
                 ->call('setContainer', [new Reference('service_container')]);
    }
}
