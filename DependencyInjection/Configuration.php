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
                    ->values(array('in_memory', 'redis', 'factory', 'config'))
                    ->defaultValue('in_memory')
                ->end()
                ->arrayNode('collection_factory')
                    ->children()
                        ->scalarNode('service_id')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('method')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
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
                ->arrayNode('toggles')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('status')->end()
                            ->arrayNode('conditions')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('name')->end()
                                        ->scalarNode('key')->end()
                                        ->arrayNode('operator')
                                            ->prototype('scalar')
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->validate()
                ->ifTrue(function ($v) {
                    if (isset($v['persistence']) && $v['persistence'] === 'factory') {
                        return ! isset($v['collection_factory']['service_id'], $v['collection_factory']['method']);
                    }

                    return false;
                })
            ->thenInvalid(
                'When choosing "factory" persistence make sure you set "collection_factory.service_id" and "collection_factory.method"')
            ->end();

        return $treeBuilder;
    }
}
