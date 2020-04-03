<?php

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
