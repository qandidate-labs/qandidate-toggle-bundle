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

namespace Qandidate\Bundle\ToggleBundle\Twig;

use Qandidate\Toggle\ContextFactory;
use Qandidate\Toggle\ToggleManager;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleTest;

class ToggleTwigExtension extends Twig_Extension
{
    private $contextFactory;
    private $toggleManager;

    public function __construct(ToggleManager $toggleManager, ContextFactory $contextFactory)
    {
        $this->toggleManager = $toggleManager;
        $this->contextFactory = $contextFactory;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function is_active($name)
    {
        return $this->toggleManager->active($name, $this->contextFactory->createContext());
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('feature_is_active', [$this, 'is_active']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return [
            new Twig_SimpleTest('active feature', [$this, 'is_active']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'qandidate_toggle_twig_extension';
    }
}
