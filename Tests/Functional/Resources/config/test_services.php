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

use Predis\Client;
use Qandidate\Bundle\ToggleBundle\Tests\TokenStorage;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('security.token_storage', TokenStorage::class)->public();
    $services->set('redis_client', Client::class);
};
