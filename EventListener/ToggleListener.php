<?php

namespace Qandidate\Bundle\ToggleBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Qandidate\Toggle\ToggleManager;
use Qandidate\Toggle\Context;
use Qandidate\Bundle\ToggleBundle\Annotations\Toggle;

class ToggleListener
{
    private $reader;
    private $toggleManager;
    private $context;

    public function __construct(Reader $reader, ToggleManager $toggleManager, Context $context)
    {
        $this->reader           = $reader;
        $this->toggleManager    = $toggleManager;
        $this->context          = $context;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $class      = ClassUtils::getClass($controller[0]);
            $object     = new \ReflectionClass($class);
            $method     = $object->getMethod($controller[1]);
        } else {
            $object     = new \ReflectionClass($controller);
            $method     = $object->getMethod('__invoke');
        }

        foreach ($this->reader->getClassAnnotations($object) as $annotation) {
            if ($annotation instanceof Toggle) {
                if (! $this->toggleManager->active($annotation->name, $this->context)) {
                    throw new NotFoundHttpException();
                }
            }
        }

        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof Toggle) {
                if (! $this->toggleManager->active($annotation->name, $this->context)) {
                    throw new NotFoundHttpException();
                }
            }
        }
    }
}
