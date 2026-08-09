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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Qandidate\Bundle\ToggleBundle\Context\UserContextFactory;
use Qandidate\Bundle\ToggleBundle\DataCollector\ToggleCollector;
use Qandidate\Bundle\ToggleBundle\EventListener\ToggleListener;
use Qandidate\Bundle\ToggleBundle\Twig\ToggleTwigExtension;
use Qandidate\Toggle\Context;
use Qandidate\Toggle\Serializer\InMemoryCollectionSerializer;
use Qandidate\Toggle\ToggleCollection\InMemoryCollection;
use Qandidate\Toggle\ToggleCollection\PredisCollection;
use Qandidate\Toggle\ToggleManager;

return static function (ContainerConfigurator $container): void {
    $parameters = $container->parameters();
    $parameters->set('qandidate.toggle.manager.class', ToggleManager::class);
    $parameters->set('qandidate.toggle.collection.in_memory.class', InMemoryCollection::class);
    $parameters->set('qandidate.toggle.collection.serializer.in_memory.class', InMemoryCollectionSerializer::class);
    $parameters->set('qandidate.toggle.collection.predis.class', PredisCollection::class);
    $parameters->set('qandidate.toggle.user_context_factory.class', UserContextFactory::class);
    $parameters->set('qandidate.toggle.twig_extension.class', ToggleTwigExtension::class);
    $parameters->set('qandidate.toggle.toggle.listener.class', ToggleListener::class);
    $parameters->set('qandidate.toggle.context.class', Context::class);
    $parameters->set('qandidate.toggle.data_collector.toggle_collector.class', ToggleCollector::class);

    $services = $container->services();

    $services->set('qandidate.toggle.collection.in_memory', '%qandidate.toggle.collection.in_memory.class%');

    $services->set('qandidate.toggle.collection.serializer.in_memory', '%qandidate.toggle.collection.serializer.in_memory.class%');

    $services->set('qandidate.toggle.manager', '%qandidate.toggle.manager.class%')
        ->public()
        ->args([service('qandidate.toggle.collection')]);

    $services->set('qandidate.toggle.user_context_factory', '%qandidate.toggle.user_context_factory.class%')
        ->public()
        ->args([service('security.token_storage')]);

    $services->set('qandidate.toggle.twig_extension', '%qandidate.toggle.twig_extension.class%')
        ->tag('twig.extension')
        ->args([
            service('qandidate.toggle.manager'),
            service('qandidate.toggle.context_factory'),
        ]);

    $services->set('qandidate.toggle.context', '%qandidate.toggle.context.class%')
        ->factory([service('qandidate.toggle.context_factory'), 'createContext']);

    $services->set('qandidate.toggle.toggle.listener', '%qandidate.toggle.toggle.listener.class%')
        ->args([
            service('qandidate.toggle.manager'),
            service('qandidate.toggle.context'),
        ])
        ->tag('kernel.event_listener', ['event' => 'kernel.controller']);

    $services->set('qandidate.toggle.data_collector.toggle_collector', '%qandidate.toggle.data_collector.toggle_collector.class%')
        ->args([
            service('qandidate.toggle.manager'),
            service('qandidate.toggle.context_factory'),
        ])
        ->tag('data_collector', [
            'id' => 'qandidate.toggle_collector',
            'template' => '@QandidateToggle\\data_collector\\toggle.html.twig',
        ]);
};
