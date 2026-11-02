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

namespace Qandidate\Bundle\ToggleBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use Qandidate\Bundle\ToggleBundle\Twig\ToggleTwigExtension;
use Qandidate\Toggle\Context;
use Qandidate\Toggle\ContextFactory;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollection\InMemoryCollection;
use Qandidate\Toggle\ToggleManager;
use Twig\Extension\ExtensionInterface;
use Twig\Test\IntegrationTestCase;

#[CoversClass(ToggleTwigExtension::class)]
class TwigIntegrationTest extends IntegrationTestCase
{
    private ToggleManager $toggleManager;

    protected function setUp(): void
    {
        $toggleCollection = new InMemoryCollection();
        $toggleCollection->set('foo', $this->createToggle('foo', true));
        $toggleCollection->set('bar', $this->createToggle('foo', false));

        $this->toggleManager = new ToggleManager($toggleCollection);
    }

    /**
     * @return list<ExtensionInterface>
     */
    protected function getExtensions(): array
    {
        $contextFactory = new class extends ContextFactory {
            public function createContext(): Context
            {
                return new Context();
            }
        };

        return [
            new ToggleTwigExtension($this->toggleManager, $contextFactory),
        ];
    }

    protected static function getFixturesDirectory(): string
    {
        return __DIR__.'/Fixtures/';
    }

    private function createToggle(string $name, bool $active): Toggle
    {
        $toggle = new Toggle($name, []);

        if ($active) {
            $toggle->activate(Toggle::ALWAYS_ACTIVE);
        } else {
            $toggle->deactivate();
        }

        return $toggle;
    }
}
