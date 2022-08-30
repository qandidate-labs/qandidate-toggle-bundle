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

namespace Qandidate\Bundle\ToggleBundle\DataCollector;

use Qandidate\Toggle\Context;
use Qandidate\Toggle\ContextFactory;
use Qandidate\Toggle\Serializer\OperatorConditionSerializer;
use Qandidate\Toggle\Serializer\OperatorSerializer;
use Qandidate\Toggle\Serializer\ToggleSerializer;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class ToggleCollector extends DataCollector
{
    /**
     * @var ToggleManager
     */
    private $toggleManager;
    /**
     * @var ContextFactory
     */
    private $contextFactory;

    public function __construct(ToggleManager $toggleManager, ContextFactory $contextFactory)
    {
        $this->toggleManager = $toggleManager;
        $this->contextFactory = $contextFactory;
    }

    /**
     * Collects data for the given Request and Response.
     *
     * @return void
     */
    public function collect(Request $request, Response $response, \Throwable $exception = null)
    {
        $serializer = new ToggleSerializer(new OperatorConditionSerializer(new OperatorSerializer()));

        $toggleData = array_map(function (Toggle $toggle) use ($serializer) {
            $result = $serializer->serialize($toggle);
            $result['active'] = $this->toggleManager->active($toggle->getName(), $this->contextFactory->createContext());
            return $result;
        }, $this->toggleManager->all());

        $this->data['toggleDetails'] = $toggleData;
        $this->data['context'] = $this->contextFactory->createContext();
    }

    public function getContext(): ?Context
    {
        return $this->data['context'];
    }

    public function getToggleDetails(): array
    {
        return $this->data['toggleDetails'];
    }

    /**
     * Returns the name of the collector.
     */
    public function getName(): string
    {
        return 'qandidate.toggle_collector';
    }

    public function reset(): void
    {
        $this->data = [];
    }
}
