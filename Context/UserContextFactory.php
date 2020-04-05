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

namespace Qandidate\Bundle\ToggleBundle\Context;

use Qandidate\Toggle\Context;
use Qandidate\Toggle\ContextFactory;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserContextFactory extends ContextFactory
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function createContext(): Context
    {
        $context = new Context();

        $token = $this->tokenStorage->getToken();

        if (null !== $token) {
            $context->set('username', $token->getUsername());
        }

        return $context;
    }
}
