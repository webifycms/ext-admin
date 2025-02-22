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

namespace Webify\Admin\Domain\Service\Menu;

/**
 * The service helps to register items to the main menu in the admin panel.
 */
interface MenuServiceInterface
{
	/**
	 * Add menu items.
	 *
	 * @param array<int, array<string, mixed>> $items
	 */
	public function addItems(array $items): void;
}
