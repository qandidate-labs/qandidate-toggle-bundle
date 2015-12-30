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

use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class QandidateToggleExtensionTest extends PHPUnit_Framework_TestCase
{
    private $containerBuilder;
    private $extension;

    public function setUp()
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->extension        = new QandidateToggleExtension();

        $this->containerBuilder->setParameter('kernel.bundles', array());
    }

    /**
     * @test
     */
    public function it_should_build_the_container_with_empty_config()
    {
        $this->mockServiceDependencies();

        $this->extension->load(array(), $this->containerBuilder);
        $this->containerBuilder->compile();
    }

    /**
     * @test
     */
    public function it_should_alias_in_memory_collection_with_empty_config()
    {
        $this->extension->load(array(), $this->containerBuilder);
        $this->assertAlias('qandidate.toggle.collection.in_memory', 'qandidate.toggle.collection');
    }

    /**
     * @test
     */
    public function it_should_alias_redis_collection_when_configured()
    {
        $this->extension->load(array(array(
            'persistence'     => 'redis',
            'redis_namespace' => 'toggle',
            'redis_client'    => 'redis_client',

        )), $this->containerBuilder);

        $this->assertAlias('qandidate.toggle.collection.predis', 'qandidate.toggle.collection');
        $this->assertAlias('redis_client', 'qandidate.toggle.redis.client');
        $this->assertParameter('toggle', 'qandidate.toggle.redis.namespace');
    }

    /**
     * @test
     */
    public function it_should_load_the_redis_service_file_when_configuring_the_redis_collection()
    {
        $this->extension->load(array(array(
            'persistence'     => 'redis',
            'redis_namespace' => 'toggle',
            'redis_client'    => 'redis_client',

        )), $this->containerBuilder);

        $this->assertTrue($this->containerBuilder->hasDefinition('qandidate.toggle.collection.predis'));
    }

    /**
     * @test
     */
    public function it_should_default_the_redis_namespace()
    {
        $this->extension->load(array(array(
            'persistence'  => 'redis',
            'redis_client' => 'redis_client',

        )), $this->containerBuilder);

        $this->assertParameter('toggle_%kernel.environment%', 'qandidate.toggle.redis.namespace');
    }

    /**
     * @test
     */
    public function it_should_create_the_toggle_collection_factory_definition()
    {
        $this->extension->load(array(array(
            'persistence' => 'factory',
            'collection_factory' => array(
                'service_id'    => 'factory.service.id',
                'method'        => 'create'
            ),
        )), $this->containerBuilder);

        $definition = $this->containerBuilder->getDefinition('qandidate.toggle.collection.factory');
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\Reference', $definition->getFactoryClass());
        $this->assertSame('factory.service.id', (string) $definition->getFactoryClass());
        $this->assertSame('create', $definition->getFactoryMethod());
    }

    /**
     * @test
     */
    public function it_should_register_the_manager()
    {
        $this->extension->load(array(), $this->containerBuilder);

        $this->assertTrue($this->containerBuilder->hasDefinition('qandidate.toggle.manager'));

        $definition = $this->containerBuilder->getDefinition('qandidate.toggle.manager');
        $arguments = $definition->getArguments();

        $this->assertCount(1, $arguments);
        $this->assertEquals('qandidate.toggle.collection', (string) $arguments[0]);
    }

    /**
     * @test
     */
    public function it_should_register_the_twig_extension()
    {
        $this->extension->load(array(), $this->containerBuilder);

        $this->assertTrue($this->containerBuilder->hasDefinition('qandidate.toggle.twig_extension'));

        $definition = $this->containerBuilder->getDefinition('qandidate.toggle.twig_extension');
        $this->assertArrayHasKey('twig.extension', $definition->getTags());
    }

    /**
     * @test
     */
    public function it_should_create_the_context_factory_alias()
    {
        $this->extension->load(array(), $this->containerBuilder);

        $this->assertAlias('qandidate.toggle.user_context_factory', 'qandidate.toggle.context_factory');
    }

    /**
     * @test
     */
    public function it_should_alias_the_context_factory_to_configured_service()
    {
        $this->extension->load(array(array('context_factory' => 'acme.yolo')), $this->containerBuilder);

        $this->assertAlias('acme.yolo', 'qandidate.toggle.context_factory');
    }

    private function assertAlias($value, $alias)
    {
        $this->assertEquals($value, (string) $this->containerBuilder->getAlias($alias), sprintf('%s alias is not correct', $alias));
    }

    private function assertParameter($value, $parameter)
    {
        $this->assertEquals($value, (string) $this->containerBuilder->getParameter($parameter), sprintf('%s parameter is not correct', $parameter));
    }

    private function mockServiceDependencies()
    {
        $this->containerBuilder->set('security.context', new \stdClass);
        $this->containerBuilder->set('annotation_reader', new \stdClass);
    }
}
