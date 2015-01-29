<?php

namespace Qandidate\Bundle\ToggleBundle\Tests\EventListener;

use Qandidate\Bundle\ToggleBundle\Annotations\Toggle;
use Qandidate\Bundle\ToggleBundle\EventListener\ToggleListener;
use Qandidate\Bundle\ToggleBundle\Tests\EventListener\Fixture\FooControllerToggleAtClassAndMethod;

use Doctrine\Common\Annotations\AnnotationReader;
use Qandidate\Toggle\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ToggleListenerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->request = $this->createRequest();
    }

    public function tearDown()
    {
        $this->listener = null;
        $this->request = null;
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testInactiveToggleAnnotationAtMethod()
    {
        $this->listener = $this->createListener(false);
        $controller = new FooControllerToggleAtClassAndMethod();

        $this->event = $this->getFilterControllerEvent(array($controller, 'barAction'), $this->request);
        $this->listener->onKernelController($this->event);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testInactiveToggleAnnotationAtClass()
    {
        $this->listener = $this->createListener(false);
        $controller = new FooControllerToggleAtClassAndMethod();

        $this->event = $this->getFilterControllerEvent(array($controller, 'bazAction'), $this->request);
        $this->listener->onKernelController($this->event);
    }

    public function testActiveToggleAnnotationAtMethod()
    {
        $this->listener = $this->createListener(true);
        $controller = new FooControllerToggleAtClassAndMethod();

        $this->event = $this->getFilterControllerEvent(array($controller, 'barAction'), $this->request);
        $this->listener->onKernelController($this->event);
        // If we end up here toggle is active, no exception thrown
        $this->assertTrue(true);
    }

    protected function createToggleManager($isToggleActive)
    {
        $toggleManager = $this->getMockBuilder('Qandidate\Toggle\ToggleManager')
            ->disableOriginalConstructor()
            ->getMock();

        $toggleManager->method('active')
            ->willReturn($isToggleActive);

        return $toggleManager;
    }

    protected function createListener($isToggleActive)
    {
        $toggleManager = $this->createToggleManager($isToggleActive);

        return new ToggleListener(new AnnotationReader(), $toggleManager, new Context());
    }

    protected function createRequest()
    {
        return new Request(array(), array(), array());
    }

    protected function getFilterControllerEvent($controller, Request $request)
    {
        $mockKernel = $this->getMockForAbstractClass('Symfony\Component\HttpKernel\Kernel', array('', ''));

        return new FilterControllerEvent($mockKernel, $controller, $request, HttpKernelInterface::MASTER_REQUEST);
    }
}

