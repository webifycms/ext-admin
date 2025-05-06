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

namespace Webify\Admin\Infrastructure\Service\ViewInjector;

use Webify\Admin\Infrastructure\Service\Menu\PrimaryMenuItemServiceInterface;
use Webify\Base\Infrastructure\Service\ViewInjector\ViewInjectorServiceInterface;

/**
 * Primary menu view injector service helps to collect and register primary menu items of the extensions.
 */
final class PrimaryMenuViewInjectorService implements ViewInjectorServiceInterface
{
	private const PRIMARY_MENU_KEY   = 'primaryMenuItems';
	private const DEFAULT_SORT_ORDER = 10;

	/**
	 * @var PrimaryMenuItemServiceInterface[]
	 */
	private array $items = [];

	/**
	 * Registers menu items into the collection if it is not already present.
	 */
	public function register(PrimaryMenuItemServiceInterface $menuItem): void
	{
		if (in_array($menuItem, $this->items, true)) {
			return;
		}

		$this->items[] = $menuItem;
	}

	public function collect(array &$data): void
	{
		$items = [];

		foreach ($this->items as $menuItem) {
			$items = array_merge($items, $menuItem->items());
		}

		usort($items, fn ($a, $b) => ($a['position'] ?? self::DEFAULT_SORT_ORDER)
			<=> ($b['position'] ?? self::DEFAULT_SORT_ORDER));

		$data[self::PRIMARY_MENU_KEY] = $items;
	}
}
