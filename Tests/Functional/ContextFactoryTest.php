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

use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\InMemoryUser;

class ContextFactoryTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->client->getContainer()->get('security.token_storage')->setToken($this->createSecurityToken());
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function it_has_the_factory_service()
    {
        $this->client->getContainer()->get('qandidate.toggle.user_context_factory');
    }

    #[Test]
    public function it_should_use_the_username_from_the_security_context()
    {
        $context = $this->client->getContainer()->get('qandidate.toggle.user_context_factory')->createContext();

        $this->assertEquals('fooUser', $context->get('username'));
    }

    private function createSecurityToken()
    {
        return new UsernamePasswordToken(new InMemoryUser('fooUser', null, ['ROLE_USER']), 'main', ['ROLE_USER']);
    }
}
