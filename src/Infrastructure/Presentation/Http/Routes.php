<?php

/**
 * The file is part of the "webifycms/ext-admin", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/admin
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Admin\Infrastructure\Presentation\Http;

use Webify\Admin\Infrastructure\Presentation\Http\Controller\Dashboard as DashboardController;
use Webify\Base\Infrastructure\Contract\Administration\AdministrationInterface;
use Webify\Base\Infrastructure\Contract\Routing\{RouterInterface, RoutesInterface};

/**
 * Sets up the routes for the administration.
 */
final readonly class Routes implements RoutesInterface
{
	public function __construct(
		private AdministrationInterface $administration
	) {}

	/**
	 * Puts the dashboard at the root of the administration address.
	 *
	 * {@inheritdoc}
	 */
	public function register(RouterInterface $router): void
	{
		$router->group($this->administration->getPath(), function (RouterInterface $router) {
			$router->get('/', DashboardController::class);
		});
	}
}
