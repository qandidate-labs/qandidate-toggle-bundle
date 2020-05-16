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

namespace Qandidate\Bundle\ToggleBundle\Twig;

use Qandidate\Toggle\ContextFactory;
use Qandidate\Toggle\ToggleManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

class ToggleTwigExtension extends AbstractExtension
{
    private $contextFactory;
    private $toggleManager;

    public function __construct(ToggleManager $toggleManager, ContextFactory $contextFactory)
    {
        $this->toggleManager = $toggleManager;
        $this->contextFactory = $contextFactory;
    }

    public function is_active(string $name): bool
    {
        return $this->toggleManager->active($name, $this->contextFactory->createContext());
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('feature_is_active', [$this, 'is_active']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return [
            new TwigTest('active feature', [$this, 'is_active']),
        ];
    }

    public function getName(): string
    {
        return 'qandidate_toggle_twig_extension';
    }
}
