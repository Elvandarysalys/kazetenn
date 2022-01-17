<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kazetenn\Core\Admin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('kazetenn');
        $treeBuilder->getRootNode()
                    ->children()
                    ->scalarNode('admin_url')
                        ->defaultValue('admin')
                    ->end()
                    ->arrayNode('menu_entries')
                        ->arrayPrototype()
                            ->children()
                                ->scalarNode('name')->end()
                                ->scalarNode('destination')->end()
                                ->scalarNode('display_name')->end()
                            ->end()
                        ->end()
                    ->end()
                    ->end();

        return $treeBuilder;
    }
}
