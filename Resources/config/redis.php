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

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('qandidate.toggle.collection.predis', '%qandidate.toggle.collection.predis.class%')
        ->args([
            '%qandidate.toggle.redis.namespace%',
            service('qandidate.toggle.redis.client'),
        ]);
};
