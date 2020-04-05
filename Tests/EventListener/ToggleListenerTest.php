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

namespace Qandidate\Bundle\ToggleBundle\Tests\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Qandidate\Bundle\ToggleBundle\Annotations\Toggle;
use Qandidate\Bundle\ToggleBundle\EventListener\ToggleListener;
use Qandidate\Bundle\ToggleBundle\Tests\EventListener\Fixture\FooControllerToggleAtClassAndMethod;
use Qandidate\Bundle\ToggleBundle\Tests\EventListener\Fixture\FooControllerToggleAtInvoke;
use Qandidate\Toggle\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ToggleListenerTest extends TestCase
{
    public function setUp(): void
    {
        $this->request = $this->createRequest();
    }

    public function tearDown(): void
    {
        $this->listener = null;
        $this->request = null;
    }

    public function testInactiveToggleAnnotationAtMethod()
    {
        $this->expectException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $this->listener = $this->createListener(false);
        $controller = new FooControllerToggleAtClassAndMethod();

        $this->event = $this->getControllerEvent([$controller, 'barAction'], $this->request);
        $this->listener->onKernelController($this->event);
    }

    public function testInactiveToggleAnnotationAtClass()
    {
        $this->expectException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $this->listener = $this->createListener(false);
        $controller = new FooControllerToggleAtClassAndMethod();

        $this->event = $this->getControllerEvent([$controller, 'bazAction'], $this->request);
        $this->listener->onKernelController($this->event);
    }

    public function testActiveToggleAnnotationAtMethod()
    {
        $this->listener = $this->createListener(true);
        $controller = new FooControllerToggleAtClassAndMethod();

        $this->event = $this->getControllerEvent([$controller, 'barAction'], $this->request);
        $this->listener->onKernelController($this->event);
        // If we end up here toggle is active, no exception thrown
        $this->assertTrue(true);
    }

    public function testInactiveToggleAnnotationAtInvoke()
    {
        $this->expectException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $this->listener = $this->createListener(false);
        $controller = new FooControllerToggleAtInvoke();

        $this->event = $this->getControllerEvent($controller, $this->request);
        $this->listener->onKernelController($this->event);
    }

    public function testActiveToggleAnnotationAtInvoke()
    {
        $this->listener = $this->createListener(true);
        $controller = new FooControllerToggleAtInvoke();

        $this->event = $this->getControllerEvent($controller, $this->request);
        $this->listener->onKernelController($this->event);
        // If we end up here toggle is active, no exception thrown
        $this->assertTrue(true);
    }

    protected function createToggleManager($isToggleActive)
    {
        $toggleManager = $this->createMock('Qandidate\Toggle\ToggleManager');

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
        return new Request([], [], []);
    }

    protected function getControllerEvent($controller, Request $request)
    {
        $mockKernel = $this->getMockForAbstractClass('Symfony\Component\HttpKernel\Kernel', ['', '']);

        return new ControllerEvent($mockKernel, $controller, $request, HttpKernelInterface::MASTER_REQUEST);
    }
}
