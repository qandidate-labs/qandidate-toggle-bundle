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

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Test;
use Qandidate\Bundle\ToggleBundle\DependencyInjection\QandidateToggleExtension;
use Qandidate\Bundle\ToggleBundle\Tests\TokenStorage;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollection\InMemoryCollection;
use Qandidate\Toggle\ToggleCollection\PredisCollection;
use Qandidate\Toggle\ToggleManager;
use Symfony\Component\DependencyInjection\Reference;

class QandidateToggleExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new QandidateToggleExtension(),
        ];
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function it_builds_the_container_with_empty_config()
    {
        $this->load();
    }

    #[Test]
    public function it_aliases_the_in_memory_collection_by_default()
    {
        $this->load();
        $this->assertContainerBuilderHasAlias('qandidate.toggle.collection', 'qandidate.toggle.collection.in_memory');
    }

    #[Test]
    public function it_aliases_the_redis_collection_when_configured()
    {
        $this->load([
            'persistence' => 'redis',
            'redis_namespace' => 'toggle',
            'redis_client' => 'redis_client',
        ]);

        $this->assertContainerBuilderHasAlias('qandidate.toggle.collection', 'qandidate.toggle.collection.predis');
        $this->assertContainerBuilderHasAlias('qandidate.toggle.redis.client', 'redis_client');
        $this->assertContainerBuilderHasParameter('qandidate.toggle.redis.namespace', 'toggle');
    }

    #[Test]
    public function it_loads_the_redis_service_file_when_configuring_the_redis_collection()
    {
        $this->load([
            'persistence' => 'redis',
            'redis_namespace' => 'toggle',
            'redis_client' => 'redis_client',
        ]);

        $this->assertContainerBuilderHasService('qandidate.toggle.collection.predis', PredisCollection::class);
    }

    #[Test]
    public function it_sets_the_default_redis_namespace()
    {
        $this->load([
            'persistence' => 'redis',
            'redis_client' => 'redis_client',
        ]);

        $this->assertContainerBuilderHasParameter('qandidate.toggle.redis.namespace', 'toggle_%kernel.environment%');
    }

    #[Test]
    public function it_creates_the_toggle_collection_factory_definition()
    {
        $this->load([
            'persistence' => 'factory',
            'collection_factory' => [
                'service_id' => 'factory.service.id',
                'method' => 'create',
            ],
        ]);

        $definition = $this->container->getDefinition('qandidate.toggle.collection.factory');
        $factory = $definition->getFactory();
        $this->assertSame(InMemoryCollection::class, $definition->getClass());
        $this->assertArrayHasKey(0, $factory);
        $this->assertArrayHasKey(1, $factory);
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\Reference', $factory[0]);
        $this->assertSame('factory.service.id', (string) $factory[0]);
        $this->assertSame('create', $factory[1]);
    }

    #[Test]
    public function it_registers_the_manager()
    {
        $this->load();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument('qandidate.toggle.manager', 0, new Reference('qandidate.toggle.collection'));
        $this->assertContainerBuilderHasService('qandidate.toggle.manager', ToggleManager::class);
    }

    #[Test]
    public function it_registers_the_twig_extension()
    {
        $this->load();

        $this->assertContainerBuilderHasServiceDefinitionWithTag('qandidate.toggle.twig_extension', 'twig.extension');
    }

    #[Test]
    public function it_creates_the_context_factory_alias()
    {
        $this->load();

        $this->assertContainerBuilderHasAlias('qandidate.toggle.context_factory', 'qandidate.toggle.user_context_factory');
    }

    #[Test]
    public function it_aliases_the_context_factory_to_configured_service()
    {
        $this->load([
            'context_factory' => 'acme.yolo',
        ]);

        $this->assertContainerBuilderHasAlias('qandidate.toggle.context_factory', 'acme.yolo');
    }

    #[Test]
    public function it_creates_a_toggle_collection_from_config()
    {
        $this->load([
            'persistence' => 'config',
            'toggles' => [
                'some_feature' => [
                    'name' => 'some_feature',
                    'status' => 'conditionally-active',
                ],
            ],
        ]);

        $this->registerService('security.token_storage', TokenStorage::class);

        $this->compile();
        $toggleCollection = $this->container->get('qandidate.toggle.collection');
        $this->assertInstanceOf(InMemoryCollection::class, $toggleCollection);

        $toggles = $toggleCollection->all();
        $this->assertCount(1, $toggles);

        $this->assertEquals(new Toggle('some_feature', []), $toggles['some_feature']);
    }
}
