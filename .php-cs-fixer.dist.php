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
use Webify\Tools\Fixer;

$finder = Finder::create()
	->in([
		__DIR__ . '/src',
		__DIR__ . '/test',
		__DIR__ . '/templates',
	])
	->name('*.php')
;

return (new Fixer($finder))
	->getConfig()
	->setUsingCache(false)
;
