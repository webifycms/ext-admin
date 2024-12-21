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

use Webify\Tools\Rector\Rector;

return (new Rector())
	->initialize(
		[
			__DIR__ . '/src',
			__DIR__ . '/test',
		]
	)->withPhpSets(php81: true)
;
