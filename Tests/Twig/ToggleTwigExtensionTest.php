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

namespace Qandidate\Bundle\ToggleBundle\Tests\Twig;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Qandidate\Bundle\ToggleBundle\Twig\ToggleTwigExtension;
use Qandidate\Toggle\Context;
use Qandidate\Toggle\ContextFactory;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollection\InMemoryCollection;
use Qandidate\Toggle\ToggleManager;

#[CoversClass(ToggleTwigExtension::class)]
class ToggleTwigExtensionTest extends TestCase
{
    private ToggleManager $toggleManager;
    private ToggleTwigExtension $extension;

    protected function setUp(): void
    {
        $this->toggleManager = new ToggleManager(new InMemoryCollection());

        $contextFactory = $this->createStub(ContextFactory::class);
        $contextFactory->method('createContext')->willReturn(new Context());

        $this->extension = new ToggleTwigExtension($this->toggleManager, $contextFactory);
    }

    #[Test]
    public function it_should_provide_an_is_active_function(): void
    {
        $functions = $this->extension->getFunctions();

        $this->assertCount(1, $functions);
        $this->assertSame('feature_is_active', $functions[0]->getName());
    }

    #[Test]
    public function it_should_return_if_a_toggle_is_active(): void
    {
        $this->assertFalse($this->extension->is_active('foo'));

        $toggle = new Toggle('foo', []);
        $toggle->activate(Toggle::ALWAYS_ACTIVE);
        $this->toggleManager->add($toggle);

        $this->assertTrue($this->extension->is_active('foo'));
    }
}
