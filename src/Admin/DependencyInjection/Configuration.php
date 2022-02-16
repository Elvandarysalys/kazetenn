<?php

namespace Kazetenn\Admin\DependencyInjection;

use Kazetenn\Admin\Model\AdminMenu;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeParentInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @return ArrayNodeDefinition|NodeBuilder|NodeDefinition|NodeParentInterface|VariableNodeDefinition|null
     */
    private function addMenuEntriesNode(){
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
                            ->end()
            ;
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        /** @formatter:on */
        $treeBuilder = new TreeBuilder('kazetenn_admin');
        $treeBuilder->getRootNode()
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
        /** @formatter:off */

        return $treeBuilder;
    }
}
