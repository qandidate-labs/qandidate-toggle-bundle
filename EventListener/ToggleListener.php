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

namespace Qandidate\Bundle\ToggleBundle\EventListener;

use Qandidate\Bundle\ToggleBundle\Annotations\Toggle;
use Qandidate\Toggle\Context;
use Qandidate\Toggle\ToggleManager;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ToggleListener
{
    private $toggleManager;
    private $context;

    public function __construct(ToggleManager $toggleManager, Context $context)
    {
        $this->toggleManager = $toggleManager;
        $this->context = $context;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $object = new \ReflectionClass($controller[0]);
            $method = $object->getMethod($controller[1]);
        } else {
            $object = new \ReflectionClass($controller);
            $method = $object->getMethod('__invoke');
        }

        foreach ([$object, $method] as $reflection) {
            foreach ($reflection->getAttributes(Toggle::class) as $attribute) {
                if (!$this->toggleManager->active($attribute->newInstance()->name, $this->context)) {
                    throw new NotFoundHttpException();
                }
            }
        }
    }
}
