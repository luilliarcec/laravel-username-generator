<?php

$rules = [
    '@PSR2' => true,
    'single_quote' => true,
    'ordered_imports' => ['sortAlgorithm' => 'alpha'],
    'visibility_required' => false,
    'blank_line_after_opening_tag' => true,
    'braces' => ['allow_single_line_closure' => true],
    'compact_nullable_typehint' => true,
    'concat_space' => ['spacing' => 'one'],
    'declare_equal_normalize' => ['space' => 'none'],
    'function_typehint_space' => true,
    'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
    'no_empty_statement' => true,
    'no_leading_import_slash' => true,
    'no_leading_namespace_whitespace' => true,
    'no_whitespace_in_blank_line' => true,
    'return_type_declaration' => ['space_before' => 'none'],
];

return (new PhpCsFixer\Config)
    ->setRules($rules)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . '/src')
            ->in(__DIR__ . '/tests')
            ->notName('README.md')
            ->notName('*.xml')
            ->notName('*.yml')
            ->notName('_ide_helper.php')
    );
