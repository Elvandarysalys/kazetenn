<?php
/*
 * This file is part of the Kazetenn Article Bundle
 *
 * (c) Gwilherm-Alan Turpin (elvandar.ysalys@protonmail.com) 2022.
 *
 * For more informations about the license and copyright, please view the LICENSE file at the root of the project.
 */

namespace Kazetenn\Articles\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('kazetenn_articles');
        $treeBuilder->getRootNode()
                    ->children()
                        ->scalarNode('article_url')
                            ->defaultValue('content')
                        ->end()
                    ->end();

        return $treeBuilder;
    }
}
