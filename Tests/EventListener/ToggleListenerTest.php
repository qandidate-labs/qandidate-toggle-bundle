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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Qandidate\Bundle\ToggleBundle\EventListener\ToggleListener;
use Qandidate\Bundle\ToggleBundle\Tests\EventListener\Fixture\FooControllerToggleAtClassAndMethod;
use Qandidate\Bundle\ToggleBundle\Tests\EventListener\Fixture\FooControllerToggleAtInvoke;
use Qandidate\Toggle\Context;
use Qandidate\Toggle\ToggleManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

#[CoversClass(ToggleListener::class)]
class ToggleListenerTest extends TestCase
{
    #[Test]
    public function it_throws_when_the_toggle_on_the_method_is_inactive(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->createListener(false)->onKernelController(
            $this->createControllerEvent([new FooControllerToggleAtClassAndMethod(), 'barAction'])
        );
    }

    #[Test]
    public function it_throws_when_the_toggle_on_the_class_is_inactive(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->createListener(false)->onKernelController(
            $this->createControllerEvent([new FooControllerToggleAtClassAndMethod(), 'bazAction'])
        );
    }

    #[Test]
    public function it_keeps_the_controller_when_the_toggle_on_the_method_is_active(): void
    {
        $controller = [new FooControllerToggleAtClassAndMethod(), 'barAction'];
        $event = $this->createControllerEvent($controller);

        $this->createListener(true)->onKernelController($event);

        $this->assertSame($controller, $event->getController());
    }

    #[Test]
    public function it_throws_when_the_toggle_on_invoke_is_inactive(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->createListener(false)->onKernelController(
            $this->createControllerEvent(new FooControllerToggleAtInvoke())
        );
    }

    #[Test]
    public function it_keeps_the_controller_when_the_toggle_on_invoke_is_active(): void
    {
        $controller = new FooControllerToggleAtInvoke();
        $event = $this->createControllerEvent($controller);

        $this->createListener(true)->onKernelController($event);

        $this->assertSame($controller, $event->getController());
    }

    private function createListener(bool $isToggleActive): ToggleListener
    {
        $toggleManager = $this->createStub(ToggleManager::class);
        $toggleManager->method('active')->willReturn($isToggleActive);

        return new ToggleListener($toggleManager, new Context());
    }

    private function createControllerEvent(callable $controller): ControllerEvent
    {
        return new ControllerEvent(
            $this->createStub(HttpKernelInterface::class),
            $controller,
            new Request(),
            HttpKernelInterface::MAIN_REQUEST
        );
    }
}
