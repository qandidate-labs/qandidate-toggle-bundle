<?php

/*
 * This file is part of the qandidate-labs/qandidate-toggle-bundle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qandidate\Bundle\ToggleBundle\Context;

use PHPUnit_Framework_TestCase;
use Qandidate\Bundle\ToggleBundle\Tests\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class UserContextFactoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->securityContext = new SecurityContext();
        $this->contextFactory  = new UserContextFactory($this->securityContext);
    }

    /**
     * @test
     */
    public function it_should_set_the_username_when_available()
    {
        $this->securityContext->setToken(new AnonymousToken('key', 'foobar'));

        $this->assertEquals('foobar', $this->contextFactory->createContext()->get('username'));
    }

    /**
     * @test
     */
    public function it_should_not_set_the_username_when_token_is_unavailable()
    {
        $this->assertFalse($this->contextFactory->createContext()->has('username'));
    }
}
