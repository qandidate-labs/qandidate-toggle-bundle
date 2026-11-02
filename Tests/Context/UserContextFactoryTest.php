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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Qandidate\Bundle\ToggleBundle\Context\UserContextFactory;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\InMemoryUser;

#[CoversClass(UserContextFactory::class)]
class UserContextFactoryTest extends TestCase
{
    private TokenStorage $tokenStorage;
    private UserContextFactory $contextFactory;

    protected function setUp(): void
    {
        $this->tokenStorage = new TokenStorage();
        $this->contextFactory = new UserContextFactory($this->tokenStorage);
    }

    #[Test]
    public function it_should_set_the_username_when_available(): void
    {
        $this->tokenStorage->setToken(new UsernamePasswordToken(new InMemoryUser('foobar', null), 'main'));

        $this->assertSame('foobar', $this->contextFactory->createContext()->get('username'));
    }

    #[Test]
    public function it_should_not_set_the_username_when_token_is_unavailable(): void
    {
        $this->assertFalse($this->contextFactory->createContext()->has('username'));
    }
}
