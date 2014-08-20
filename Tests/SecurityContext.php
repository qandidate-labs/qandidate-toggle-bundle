<?php

/*
 * This file is part of the qandidate-labs/qandidate-toggle-bundle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qandidate\Bundle\ToggleBundle\Tests;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SecurityContext implements SecurityContextInterface
{
    private $token;

    /**
     * {@inheritDoc}
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * {@inheritDoc}
     */
    public function setToken(TokenInterface $token = null)
    {
        $this->token = $token;
    }

    /**
     * {@inheritDoc}
     */
    public function isGranted($attributes, $object = null)
    {
        return null !== $this->token;
    }
}
