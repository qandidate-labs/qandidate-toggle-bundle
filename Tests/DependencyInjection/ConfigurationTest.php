<?php

/*
 * This file is part of the qandidate/toggle-bundle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Qandidate\Bundle\ToggleBundle\Tests\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Qandidate\Bundle\ToggleBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }

    /**
     * @test
     */
    public function it_accepts_empty_configuration_and_configures_defaults()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [],
            ],
            [
                'persistence' => 'in_memory',
                'context_factory' => null,
                'redis_namespace' => 'toggle_%kernel.environment%',
                'redis_client' => null,
                'toggles' => [],
            ]
        );
    }

    /**
     * @test
     */
    public function it_defaults_to_in_memory_persistence()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [],
            ],
            [
                'persistence' => 'in_memory',
            ],
            'persistence'
        );
    }

    /**
     * @test
     */
    public function it_configures_toggles_without_conditions()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'toggles' => [
                        'always-active-feature' => [
                            'name' => 'always-active-feature',
                            'status' => 'always-active',
                        ],
                        'inactive-feature' => [
                            'name' => 'inactive-feature',
                            'status' => 'inactive',
                        ],
                    ],
                ],
            ],
            [
                'toggles' => [
                    'always_active_feature' => [
                        'name' => 'always-active-feature',
                        'status' => 'always-active',
                        'conditions' => [],
                    ],
                    'inactive_feature' => [
                        'name' => 'inactive-feature',
                        'status' => 'inactive',
                        'conditions' => [],
                    ],
                ],
            ],
            'toggles'
        );
    }

    /**
     * @test
     */
    public function it_configures_toggles_with_conditions()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'toggles' => [
                        'conditionally-active' => [
                            'name' => 'conditionally-active',
                            'status' => 'conditionally-active',
                            'conditions' => [
                                [
                                    'name' => 'operator-condition',
                                    'key' => 'user_id',
                                    'operator' => [
                                       'name' => 'greater-than',
                                       'value' => 42,
                                    ],
                                ],
                            ],
                         ],
                    ],
                ],
            ],
            [
                'toggles' => [
                    'conditionally_active' => [
                        'name' => 'conditionally-active',
                        'status' => 'conditionally-active',
                        'conditions' => [
                            [
                                'name' => 'operator-condition',
                                'key' => 'user_id',
                                'operator' => [
                                    'name' => 'greater-than',
                                    'value' => 42,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'toggles'
        );
    }

    /**
     * @test
     */
    public function it_configures_toggles_with_inset_operator()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'toggles' => [
                        'conditionally-active' => [
                            'name' => 'conditionally-active',
                            'status' => 'conditionally-active',
                            'conditions' => [
                                [
                                    'name' => 'operator-condition',
                                    'key' => 'user_id',
                                    'operator' => [
                                       'name' => 'greater-than',
                                       'values' => [41, 42],
                                    ],
                                ],
                            ],
                         ],
                    ],
                ],
            ],
            [
                'toggles' => [
                    'conditionally_active' => [
                        'name' => 'conditionally-active',
                        'status' => 'conditionally-active',
                        'conditions' => [
                            [
                                'name' => 'operator-condition',
                                'key' => 'user_id',
                                'operator' => [
                                    'name' => 'greater-than',
                                    'values' => [41, 42],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'toggles'
        );
    }

    /**
     * @test
     */
    public function it_requires_collection_factory_to_be_set_when_persistence_is_factory()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Invalid configuration for path "qandidate_toggle": When choosing "factory" persistence make sure you set "collection_factory.service_id" and "collection_factory.method"');

        $this->assertProcessedConfigurationEquals(
            [
                [
                    'persistence' => 'factory',
                ],
            ],
            [
                'persistence' => 'factory',
            ],
            'persistence'
        );
    }
}
