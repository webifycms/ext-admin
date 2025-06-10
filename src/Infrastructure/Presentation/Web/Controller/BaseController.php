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

use Webify\Admin\Domain\AdminExtensionInterface;
use Webify\Base\Infrastructure\Presentation\Web\Controller\WebController;

/**
 * Base controller class for this extension, it helps to set a template path and template theme support.
 */
class BaseController extends WebController
{
	public function init(): void
	{
		$this->layout = AdminExtensionInterface::TEMPLATES_PATH . '/layouts/main.php';

		$this->setViewPath(AdminExtensionInterface::TEMPLATES_PATH);
		$this->addThemeSupport(
			[
				$this->getViewPath() => '@Theme/templates/admin',
			]
		);
		parent::init();
	}
}
