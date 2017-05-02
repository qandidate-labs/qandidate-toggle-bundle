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
class InMemoryCollectionFactory
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var InMemoryCollectionSerializer
     */
    private $serializer;

    /**
     * @param array $config
     * @param InMemoryCollectionSerializer $serializer
     */
    public function __construct(array $config, InMemoryCollectionSerializer $serializer)
    {
        $this->config = $config;
        $this->serializer = $serializer;
    }

    /**
     * @return \Qandidate\Toggle\ToggleCollection\InMemoryCollection
     */
    public function getToggles()
    {
        return $this->serializer->deserialize($this->config);
    }
}
