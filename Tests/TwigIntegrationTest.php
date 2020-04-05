<?php

declare(strict_types=1);

namespace Qandidate\Bundle\ToggleBundle\Tests;

use Qandidate\Bundle\ToggleBundle\Twig\ToggleTwigExtension;
use Qandidate\Toggle\Context;
use Qandidate\Toggle\ContextFactory;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollection\InMemoryCollection;
use Qandidate\Toggle\ToggleManager;
use Twig_Test_IntegrationTestCase;

class TwigIntegrationTest extends Twig_Test_IntegrationTestCase
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
