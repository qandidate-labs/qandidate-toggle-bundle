<?php

$config = require 'vendor/broadway/coding-standard/.php-cs-fixer.dist.php';

$config->setFinder(
    \PhpCsFixer\Finder::create()
        ->in(__DIR__)
        ->exclude([
            'vendor',
            'var',
        ])
);

return $config;
