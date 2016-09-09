<?php
namespace Qandidate\Bundle\ToggleBundle\Factory;

use Qandidate\Toggle\Serializer\InMemoryCollectionSerializer;

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