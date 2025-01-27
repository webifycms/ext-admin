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

use yii\web\GroupUrlRule;

use function Webify\Base\Infrastructure\administration_path;

return [
	new GroupUrlRule([
		'prefix' => administration_path(),
		'rules'  => [
			'<controller:[\w\-]+>'                  => '<controller>/index',
			'<controller:[\w\-]+>/<action:[\w\-]+>' => '<controller>/<action>',
		],
	]),
];
