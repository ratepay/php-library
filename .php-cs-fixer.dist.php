<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$header = <<<EOF
Ratepay PHP-Library

This document contains trade secret data which are the property of
Ratepay GmbH, Berlin, Germany. Information contained herein must not be used,
copied or disclosed in whole or part unless permitted in writing by Ratepay GmbH.
All rights reserved by Ratepay GmbH.

Copyright (c) 2019 Ratepay GmbH / Berlin / Germany
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
