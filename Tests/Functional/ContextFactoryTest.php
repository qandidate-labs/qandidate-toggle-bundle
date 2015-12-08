<?php

/*
 * This file is part of the qandidate-labs/qandidate-toggle-bundle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qandidate\Bundle\ToggleBundle\Tests\Functional;

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class ContextFactoryTest extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->client->getContainer()->get('security.token_storage')->setToken($this->createSecurityToken());
    }

    /**
     * @test
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
        return new AnonymousToken('userKey', 'fooUser', array('ROLE_USER'));
    }
}
