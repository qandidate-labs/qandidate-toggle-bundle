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

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

require __DIR__.'/vendor/autoload.php';

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles()
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Qandidate\Bundle\ToggleBundle\QandidateToggleBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $c->loadFromExtension('framework', [
            'secret' => 'Everything is awesome',
        ]);

        // minimal configuration to enable security bundle
        $c->loadFromExtension('security', [
            'providers' => [
                'my_custom_provider' => [
                    'memory' => [],
                ],
            ],
            'firewalls' => [
                'my_firewall' => [
                    'anonymous' => [],
                ],
            ],
        ]);

        $c->loadFromExtension('qandidate_toggle', [
            'persistence' => 'config',
            'toggles' => [
                'always-active-feature' => [
                    'name' => 'always-active-feature',
                    'status' => 'always-active',
                ],
                'inactive-feature' => [
                    'name' => 'inactive-feature',
                    'status' => 'inactive',
                ],
                'conditionally-active' => [
                    'name' => 'conditionally-active',
                    'status' => 'conditionally-active',
                    'conditions' => [
                        [
                            'name' => 'operator-condition',
                            'key' => 'user_id',
                            'operator' => [
                                'name' => 'greater-than',
                                'value' => 42,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->add('/', 'kernel:indexAction');
    }

    public function indexAction()
    {
        $toggleManager = $this->getContainer()->get('qandidate.toggle.manager');
        $context = new \Qandidate\Toggle\Context();
        $context->set('user_id', 43);

        $output = array_map(function (Qandidate\Toggle\Toggle $toggle) use ($toggleManager, $context) {
            return [
                'name' => $toggle->getName(),
                'active' => $toggleManager->active($toggle->getName(), $context),
            ];
        }, $toggleManager->all());

        return new JsonResponse([
            'context' => $context->toArray(),
            'toggles' => $output,
        ]);
    }
}

$kernel = new AppKernel('dev', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
