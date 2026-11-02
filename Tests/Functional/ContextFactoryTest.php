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

namespace Qandidate\Bundle\ToggleBundle\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use Qandidate\Bundle\ToggleBundle\Context\UserContextFactory;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\InMemoryUser;

#[CoversNothing]
class ContextFactoryTest extends WebTestCase
{
    private UserContextFactory $contextFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $container = static::getContainer();

        $tokenStorage = $container->get('security.token_storage');
        $this->assertInstanceOf(TokenStorageInterface::class, $tokenStorage);
        $tokenStorage->setToken(new UsernamePasswordToken(new InMemoryUser('fooUser', null, ['ROLE_USER']), 'main', ['ROLE_USER']));

        $contextFactory = $container->get('qandidate.toggle.user_context_factory');
        $this->assertInstanceOf(UserContextFactory::class, $contextFactory);
        $this->contextFactory = $contextFactory;
    }

    #[Test]
    public function it_should_use_the_username_from_the_security_context(): void
    {
        $this->assertSame('fooUser', $this->contextFactory->createContext()->get('username'));
    }
}
