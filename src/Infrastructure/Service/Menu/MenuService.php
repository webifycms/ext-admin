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

use Webify\Admin\Domain\Service\Menu\MenuServiceInterface;
use yii\base\View as BaseView;
use yii\web\View;

/**
 * Administration menu service class that helps to manage the administration menu.
 */
final class MenuService implements MenuServiceInterface
{
	/**
	 * The object constructor.
	 */
	public function __construct(
		private readonly BaseView|View $view
	) {}

	public function addItems(array $items): void
	{
		foreach ($items as $item) {
			$this->view->params['primaryMenuItems'][] = $item;
		}

		$this->sortItems();
	}

	/**
	 * Sort menu items based on the given position.
	 */
	private function sortItems(): void
	{
		if (!empty($this->view->params['primaryMenuItems'])) {
			usort($this->view->params['primaryMenuItems'], function ($a, $b) {
				$a['position'] ??= 10;
				$b['position'] ??= 10;

				return $a['position'] <=> $b['position'];
			});
		}
	}
}
