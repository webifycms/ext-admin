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

namespace Webify\Admin\Test\Unit\Infrastructure\Presentation\Http;

use PHPUnit\Framework\Attributes\{CoversClass, Test};
use PHPUnit\Framework\TestCase;
use Webify\Admin\Infrastructure\Presentation\Http\Controller\Dashboard as DashboardController;
use Webify\Admin\Infrastructure\Presentation\Http\Routes;
use Webify\Base\Infrastructure\Contract\Administration\AdministrationInterface;
use Webify\Base\Infrastructure\Contract\Routing\RouterInterface;

/**
 * Proves the routes are put under the administration address.
 *
 * @internal
 */
#[CoversClass(Routes::class)]
final class RoutesTest extends TestCase
{
	/**
	 * The route group uses the administration's own address, so the place
	 * where routes live matches the place the check looks for them.
	 */
	#[Test]
	public function testTheAdministrationPathFeedsTheRouteGroup(): void
	{
		$administration = self::createStub(AdministrationInterface::class);
		$administration->method('getPath')->willReturn('admin');

		$router = self::createMock(RouterInterface::class);
		$router->expects(self::once())
			->method('group')
			->with('admin', self::isCallable())
			->willReturnSelf()
		;

		new Routes($administration)->register($router);
	}

	/**
	 * The routes are added inside the group, and the dashboard goes to the
	 * root of the administration address.
	 */
	#[Test]
	public function testTheGroupCallbackReceivesARouter(): void
	{
		$administration = self::createStub(AdministrationInterface::class);
		$administration->method('getPath')->willReturn('admin');

		$router = self::createMock(RouterInterface::class);
		$router->expects(self::once())
			->method('group')
			->with(
				'admin',
				self::callback(
					function (mixed $callback): bool {
						self::assertIsCallable($callback);

						$inner = $this->createMock(RouterInterface::class);
						$inner->expects(self::once())
							->method('get')
							->with('/', DashboardController::class)
							->willReturnSelf()
						;

						$callback($inner);

						return true;
					}
				)
			)
			->willReturnSelf()
		;

		new Routes($administration)->register($router);
	}
}
