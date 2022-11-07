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

namespace Qandidate\Bundle\ToggleBundle\Tests\Context;

use PHPUnit\Framework\TestCase;
use Qandidate\Bundle\ToggleBundle\Context\UserContextFactory;
use Qandidate\Bundle\ToggleBundle\Tests\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\InMemoryUser;

class UserContextFactoryTest extends TestCase
{
    public function setUp(): void
    {
        $this->tokenStorage = new TokenStorage();
        $this->contextFactory = new UserContextFactory($this->tokenStorage);
    }

    /**
     * @test
     */
    public function it_should_set_the_username_when_available()
    {
        $this->tokenStorage->setToken(new UsernamePasswordToken(new InMemoryUser('my_username', 'password'), 'firewall'));

        $this->assertEquals('my_username', $this->contextFactory->createContext()->get('username'));
    }

    /**
     * @test
     */
    public function it_should_not_set_the_username_when_token_is_unavailable()
    {
        $this->assertFalse($this->contextFactory->createContext()->has('username'));
    }
}
