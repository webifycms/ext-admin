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

namespace Webify\Admin\Infrastructure;

use Webify\Admin\Infrastructure\Asset\AdminAsset;
use Webify\Admin\Infrastructure\Service\Administration\AdministrationMenuService;
use Webify\Base\Domain\Exception\TranslatableRuntimeException;
use Webify\Base\Domain\Service\Theme\ThemeInterface;
use yii\base\Module;
use yii\web\View;

/**
 * Administration module.
 */
final class AdminModule extends Module
{
	/**
	 * @todo Should check weather this property is necessary.
	 */
	public string $basePath = '@Admin';

	public $controllerNamespace = 'Webify\Admin\Presentation\Web\Admin\Controller';

	public $defaultRoute = 'dashboard/index';

	public $layout = 'main';

	public AdministrationMenuService $menuService;

	public function init(): void
	{
		parent::init();
		$this->setViewPath('@Admin/templates');

		// adding theme support for the templates and register assets
		try {
			$view              = $this->get('view');
			$this->menuService = new AdministrationMenuService($view);

			$this->registerMenuItems();
			//			$this->addThemeSupport($view);
			// $this->registerAssets($view);
		} catch (\Throwable $exception) {
			throw new TranslatableRuntimeException(
				'admin.view_component_error',
				[],
				$exception->getCode(),
				$exception
			);
		}
	}

	/**
	 * Registering admin menu items.
	 */
	private function registerMenuItems(): void
	{
		$this->menuService->addItems([
			[
				'label'    => 'Dashboard',
				'icon'     => 'speedometer',
				'route'    => ["/{$this->id}"],
				'position' => 0,
			],
			[
				'label'    => 'Settings',
				'icon'     => 'sliders',
				'route'    => '#',
				'position' => 10,
			],
		]);
	}

	/**
	 * Add theme support for this module.
	 */
	private function addThemeSupport(View $view): void
	{
		if ($view->theme instanceof ThemeInterface) {
			$view->theme->pathMap = array_merge($view->theme->pathMap, [
				'@Admin/templates' => '@App/themes/' . $view->theme->getId() . '/templates/admin',
			]);
		}
	}

	private function registerAssets(View $view): void
	{
		$view->params['adminAsset'] = AdminAsset::register($view);
	}
}
