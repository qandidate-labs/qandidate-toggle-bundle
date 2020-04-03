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

    /**
     * ToggleCollector constructor.
     */
    public function __construct(ToggleManager $toggleManager, ContextFactory $contextFactory)
    {
        $this->toggleManager = $toggleManager;
        $this->contextFactory = $contextFactory;
    }

    /**
     * Collects data for the given Request and Response.
     *
     * @param Request    $request   A Request instance
     * @param Response   $response  A Response instance
     * @param \Exception $exception An Exception instance
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $serializer = new ToggleSerializer(new OperatorConditionSerializer(new OperatorSerializer()));

        $toggleData = array_map(function (Toggle $toggle) use ($serializer) {
            return $serializer->serialize($toggle);
        }, $this->toggleManager->all());

        $this->data['toggleDetails'] = $toggleData;
        $this->data['context'] = $this->contextFactory->createContext();
    }

    /**
     * @return Context
     */
    public function getContext()
    {
        return $this->data['context'];
    }

    /**
     * @return array
     */
    public function getToggleDetails()
    {
        return $this->data['toggleDetails'];
    }

    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     */
    public function getName()
    {
        return 'qandidate.toggle_collector';
    }

    public function reset()
    {
        $this->data = [];
    }
}
