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

namespace Webify\Admin\Infrastructure\Service\Administration;

use Webify\Base\Infrastructure\Service\Administration\PrimaryMenuItemsAdministrationServiceInterface;

use function Webify\Base\Infrastructure\administration_url;
use function Webify\Base\Infrastructure\url;

/**
 * Service class to register primary menu items for the admin extension.
 */
final class PrimaryMenuItemsAdministrationService implements PrimaryMenuItemsAdministrationServiceInterface
{
	public function __construct(
		private readonly string $assetsUrl,
	) {}

	/**
	 * TODO: Currently the Menu widget does not support img tag for icon, should replaced when the request is merged.
	 */
	public function getItems(): array
	{
		return [
			[
				'label'     => sprintf(
					'<img src="%s" alt="%s"><div>Dashboard</div>',
					url($this->assetsUrl . '/icons/dashboard.svg'),
					'Dashboard'
				),
				'encode'    => false,
				'link'      => administration_url('dashboard/index'),
				'position'  => 0,
			],
		];
	}
}
