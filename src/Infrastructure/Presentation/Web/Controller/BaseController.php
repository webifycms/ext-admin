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

namespace Webify\Admin\Infrastructure\Presentation\Web\Controller;

use Webify\Admin\Infrastructure\Service\Bootstrap\WebBootstrapService;
use Webify\Base\Infrastructure\Component\Theme\ThemeComponent;
use Webify\Base\Infrastructure\Presentation\Web\Controller\WebController;

/**
 * Base controller class for this extension, it helps to set templates path and template theme support.
 */
class BaseController extends WebController
{
	public function init(): void
	{
		$this->layout = WebBootstrapService::TEMPLATES_PATH . '/layouts/main';

		$this->setViewPath(WebBootstrapService::TEMPLATES_PATH);
		$this->addThemeSupport();
		parent::init();
	}

	/**
	 * Add theme support for the view files.
	 */
	private function addThemeSupport(): void
	{
		$theme = $this->view->theme;

		if ($theme instanceof ThemeComponent) {
			$theme->addToPathMap(
				[
					$this->getViewPath() => '@Theme/templates/admin',
				]
			);
		}
	}
}
