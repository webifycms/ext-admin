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

namespace Webify\Admin\Infrastructure\Service\Menu;

/**
 * Interface for registering primary menu items of the extension for the administration.
 */
interface PrimaryMenuItemServiceInterface
{
	/**
	 * Add items to the primary menu.
	 *
	 * @return array<array<string, mixed>>
	 */
	public function items(): array;
}
