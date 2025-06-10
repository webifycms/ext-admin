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

use Webify\Base\Infrastructure\Service\Register\Dependencies\DependenciesRegisterService as AbstractDependenciesRegisterService;

use function Webify\Base\Infrastructure\get_alias;

/**
 * The service class to register dependencies for the Admin extension.
 */
final class DependenciesRegisterService extends AbstractDependenciesRegisterService
{
	/**
	 * @return array<string, mixed>
	 */
	public function getDependencies(): array
	{
		return require get_alias('@Admin/config/dependencies.php');
	}
}
