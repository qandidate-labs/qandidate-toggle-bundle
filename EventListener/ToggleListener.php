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

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Qandidate\Bundle\ToggleBundle\Annotations\Toggle;
use Qandidate\Toggle\Context;
use Qandidate\Toggle\ToggleManager;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ToggleListener
{
    private $reader;
    private $toggleManager;
    private $context;

    public function __construct(Reader $reader, ToggleManager $toggleManager, Context $context)
    {
        $this->reader = $reader;
        $this->toggleManager = $toggleManager;
        $this->context = $context;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $class = ClassUtils::getClass((object) $controller[0]);
            $object = new \ReflectionClass($class);
            $method = $object->getMethod($controller[1]);
        } else {
            $object = new \ReflectionClass($controller);
            $method = $object->getMethod('__invoke');
        }

        foreach ($this->reader->getClassAnnotations($object) as $annotation) {
            if ($annotation instanceof Toggle) {
                if (!$this->toggleManager->active($annotation->name, $this->context)) {
                    throw new NotFoundHttpException();
                }
            }
        }

        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof Toggle) {
                if (!$this->toggleManager->active($annotation->name, $this->context)) {
                    throw new NotFoundHttpException();
                }
            }
        }
    }
}
