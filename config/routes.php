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

use function Webify\Admin\Infrastructure\administration_path;

$adminPath = administration_path();

return [
	[
		'route'      => $adminPath,
		'pattern'    => $adminPath,
		'normalizer' => false,
		'suffix'     => false,
	],
	[
		'route'      => $adminPath . '/<controller>/<action>',
		'pattern'    => $adminPath . '/<controller:[\w\-]+>/<action:[\w\-]+>',
		'normalizer' => false,
		'suffix'     => false,
	],
];
