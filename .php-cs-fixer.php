<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__.'/Attribute',
        __DIR__.'/Context',
        __DIR__.'/DataCollector',
        __DIR__.'/DependencyInjection',
        __DIR__.'/EventListener',
        __DIR__.'/Tests',
        __DIR__.'/Twig',
    ]);

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'declare_strict_types' => true,
        'php_unit_method_casing' => ['case' => 'snake_case'],
    ])
    ->setFinder($finder);
