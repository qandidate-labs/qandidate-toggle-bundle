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

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class ContextFactoryTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->client->getContainer()->get('security.token_storage')->setToken($this->createSecurityToken());
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_has_the_factory_service()
    {
        $this->client->getContainer()->get('qandidate.toggle.user_context_factory');
    }

    /**
     * @test
     */
    public function it_should_use_the_username_from_the_security_context()
    {
        $context = $this->client->getContainer()->get('qandidate.toggle.user_context_factory')->createContext();

        $this->assertEquals('fooUser', $context->get('username'));
    }

    private function createSecurityToken()
    {
        return new AnonymousToken('userKey', 'fooUser', ['ROLE_USER']);
    }
}
