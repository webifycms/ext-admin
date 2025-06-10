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

namespace Webify\Admin\Infrastructure\Service\Register;

use Webify\Base\Infrastructure\Service\Register\Controllers\ControllerNamespaceRegisterService as AbstractControllerNamespaceRegisterService;

/**
 * The service class to register controller namespaces for the Admin extension.
 */
final class ControllerNamespaceRegisterService extends AbstractControllerNamespaceRegisterService
{
	public function getNamespaces(): array
	{
		return [
			'Webify\Admin\Infrastructure\Presentation\Web\Controller\Admin',
		];
	}
}
