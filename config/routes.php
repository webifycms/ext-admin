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

return [
	'<controller:[A-Za-z0-9\-]+>/<action:[\w\-]+>'  => '<controller>/<action>',
	'<controller:[A-Za-z0-9\-]+>'                   => '<controller>/index',
	''                                              => 'dashboard/index',
];
