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

namespace Webify\Admin\Infrastructure\Presentation\Web\Controller\Admin;

use Webify\Base\Infrastructure\Presentation\Web\Controller\WebController;

/**
 * DashboardController.
 */
final class DashboardController extends WebController
{
	public function actionIndex(): string
	{
		$this->view->title = 'Dashboard';

		return $this->render('index');
	}

	public function actionUpdates(): string
	{
		return $this->render('updates');
	}
}
