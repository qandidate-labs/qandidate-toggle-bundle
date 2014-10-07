<?php

/*
 * This file is part of the qandidate-labs/qandidate-toggle-bundle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qandidate\Bundle\ToggleBundle\Twig;

use PHPUnit_Framework_TestCase;
use Qandidate\Toggle\Context;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleManager;
use Qandidate\Toggle\ToggleCollection\InMemoryCollection;

class ToggleTwigExtensionTest extends PHPUnit_Framework_TestCase
{
    private $contextFactory;
    private $extension;
    private $toggleManager;

    public function setUp()
    {
        $this->toggleManager  = new ToggleManager(new InMemoryCollection());
        $this->contextFactory = $this->getMockBuilder('Qandidate\Toggle\ContextFactory')
            ->disableOriginalConstructor()
            ->setMethods(array('createContext'))
            ->getMock();

        $this->extension = new ToggleTwigExtension($this->toggleManager, $this->contextFactory);
    }

    /**
     * @test
     */
    public function it_should_provide_an_is_active_function()
    {
        $functions = $this->extension->getFunctions();

        $this->assertCount(1, $functions);
        $this->assertInstanceof('Twig_SimpleFunction', $functions[0]);
        $this->assertEquals('feature_is_active', $functions[0]->getName());
    }

    /**
     * @test
     */
    public function it_should_return_if_a_toggle_is_active()
    {
        $this->contextFactory
            ->expects($this->any())
            ->method('createContext')
            ->will($this->returnValue($this->createEmptyContext()));

        $this->assertFalse($this->extension->is_active('foo'));

        $this->toggleManager->add($this->createToggle('foo', Toggle::ALWAYS_ACTIVE));

        $this->assertTrue($this->extension->is_active('foo'));
    }

    private function createEmptyContext()
    {
        return new Context();
    }

    private function createToggle($name, $status, array $conditions = array())
    {
        $toggle = new Toggle($name, $conditions);
        $toggle->activate($status);

        return $toggle;
    }
}
