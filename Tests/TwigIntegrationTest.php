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

use Qandidate\Bundle\ToggleBundle\Twig\ToggleTwigExtension;
use Qandidate\Toggle\Context;
use Qandidate\Toggle\ContextFactory;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollection\InMemoryCollection;
use Qandidate\Toggle\ToggleManager;
use Twig\Test\IntegrationTestCase;

class TwigIntegrationTest extends IntegrationTestCase
{
    private $contextFactory;
    private $toggleManager;

    public function setUp(): void
    {
        $toggleCollection = new InMemoryCollection();
        $toggleCollection->set('foo', $this->createToggle('foo', true));
        $toggleCollection->set('bar', $this->createToggle('foo', false));

        $this->contextFactory = new StubContextFactory();
        $this->toggleManager = new ToggleManager($toggleCollection);
    }

    public function getExtensions()
    {
        return [
            new ToggleTwigExtension($this->toggleManager, $this->contextFactory),
        ];
    }

    public function getFixturesDir()
    {
        return __DIR__.'/Fixtures/';
    }

    private function createToggle($name, $active)
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

class StubContextFactory extends ContextFactory
{
    public function createContext(): Context
    {
        return new Context();
    }
}
