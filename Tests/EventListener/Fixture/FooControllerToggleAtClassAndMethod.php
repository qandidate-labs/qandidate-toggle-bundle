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

/**
 * @Toggle("cool-feature")
 */
class FooControllerToggleAtClassAndMethod
{
    const METHOD_EXECUTED = 'method.executed';

    /**
     * @Toggle("another-cool-feature")
     */
    public function barAction()
    {
        return self::METHOD_EXECUTED;
    }

    public function bazAction()
    {
        return self::METHOD_EXECUTED;
    }
}
