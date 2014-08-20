<?php

/*
 * This file is part of the qandidate-labs/qandidate-toggle-bundle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qandidate\Bundle\ToggleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('qandidate_toggle');

        $rootNode
            ->children()
                ->enumNode('persistence')
                    ->values(array('in_memory', 'redis'))
                    ->defaultValue('in_memory')
                ->end()
                ->scalarNode('context_factory')
                    ->defaultNull()
                ->end()
                ->scalarNode('redis_namespace')
                    ->defaultValue('toggle_%kernel.environment%')
                ->end()
                ->scalarNode('redis_client')
                    ->defaultNull()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
