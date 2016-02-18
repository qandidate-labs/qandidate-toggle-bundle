<?php

/*
 * This file is part of the qandidate-labs/qandidate-toggle-bundle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qandidate\Bundle\ToggleBundle\Factory;

use Qandidate\Toggle\Serializer\InMemoryCollectionSerializer;

/**
 * Class SymfonyCollection
 * @package Qandidate\Bundle\ToggleBundle\Factory
 */
class SymfonyCollection
{
    /**
     * @var array
     */
    private $config;

    /**
     * SymfonyCollection constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Qandidate\Toggle\ToggleCollection\InMemoryCollection
     */
    public function getToggles()
    {
        $serializer = new InMemoryCollectionSerializer();
        return $serializer->deserialize($this->config);
    }
}