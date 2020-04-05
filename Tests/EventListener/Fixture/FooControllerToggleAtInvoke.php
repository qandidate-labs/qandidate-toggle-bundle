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

namespace Qandidate\Bundle\ToggleBundle\Tests\EventListener\Fixture;

use Qandidate\Bundle\ToggleBundle\Annotations\Toggle;

class FooControllerToggleAtInvoke
{
    /**
     * @Toggle("cool-feature-on-invoke")
     */
    public function __invoke()
    {
        return 'method.executed';
    }
}
