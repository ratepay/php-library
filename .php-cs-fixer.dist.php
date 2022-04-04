<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$header = <<<EOF
Copyright (c) Ratepay GmbH

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src');

$rules = [
    '@PSR1' => true,
    '@PSR2' => true,
    '@Symfony' => true,
    'header_comment' => ['header' => $header],
    'yoda_style' => false,
    'no_blank_lines_after_class_opening' => true,
    'list_syntax' => ['syntax' => 'short'],
    'no_unused_imports' => true,
    'concat_space' => ['spacing' => 'one'],
    'trailing_comma_in_multiline' => true,
    'no_blank_lines_after_phpdoc' => true,
    'class_attributes_separation' => true,
];

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setFinder($finder);
