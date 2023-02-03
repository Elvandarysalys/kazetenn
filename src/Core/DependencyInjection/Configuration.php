<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kazetenn\Core\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('kazetenn');

//        $treeBuilder->getRootNode()
//                        ->children()
//                            ->scalarNode('menu_url')
//                                ->defaultValue('menu')
//                            ->end()
//                        ->end()
//                    ->end();

        return $treeBuilder;
    }
}
