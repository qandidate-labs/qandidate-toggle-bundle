<?php

declare(strict_types=1);

/*
 * This file is part of the qandidate-labs/qandidate-toggle-bundle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qandidate\Bundle\ToggleBundle\Context;

use Qandidate\Toggle\Context;
use Qandidate\Toggle\ContextFactory;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserContextFactory extends ContextFactory
{
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function createContext()
    {
        $context = new Context();

        $token = $this->tokenStorage->getToken();

        if (null !== $token) {
            $context->set('username', $this->tokenStorage->getToken()->getUsername());
        }

        return $context;
    }
}
