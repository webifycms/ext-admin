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

use Webify\Base\Domain\Service\Application\ApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use yii\base\View as BaseView;
use yii\web\View;

/**
 * Administration menu service class that helps to manage the administration menu.
 */
final class AdministrationMenuService
{
	private readonly View|BaseView $view;

	/**
	 * The object constructor.
	 */
	public function __construct(
		ApplicationServiceInterface|WebApplicationServiceInterface $appService
	) {
		$this->view = $appService->getApplication()->getView();
	}

	/**
	 * Add menu items.
	 *
	 * @param array<array<string, int|string>> $items
	 */
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
