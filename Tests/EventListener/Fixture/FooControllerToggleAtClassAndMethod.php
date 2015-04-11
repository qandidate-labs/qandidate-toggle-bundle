<?php

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
