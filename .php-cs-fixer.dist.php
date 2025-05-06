<?php

/**
 * The file is part of the "webifycms/ext-admin", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/admin
 *
 * @copyright Copyright (c) 2023 WebifyCMS
 * @license https://webifycms.com/extension/admin/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

// should require the composer autoloader on first
require __DIR__ . '/vendor/autoload.php';

use PhpCsFixer\Finder;
use Webify\Tools\Fixer\Fixer;

$finder = Finder::create()
	->in(__DIR__)
	->exclude(
		[
			'vendor',
		]
	)
	->ignoreDotFiles(false)
	->name('*.php')
;
$rules = [
	'echo_tag_syntax' => [
		'format'                         => 'short',
		'shorten_simple_statements_only' => false,
	],
	'phpdoc_to_comment'       => false,
	'global_namespace_import' => [
		'import_classes'   => true,
		'import_constants' => false,
		'import_functions' => true,
	],
];

return (new Fixer($finder, $rules))
	->getConfig()
	->setUsingCache(false)
;
