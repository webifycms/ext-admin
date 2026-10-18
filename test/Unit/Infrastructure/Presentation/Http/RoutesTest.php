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
use Webify\Base\Infrastructure\Contract\AdministrationInterface;
use Webify\Base\Infrastructure\Contract\Routing\RouterInterface;

/**
 * RoutesTest tests the administration's route registration.
 *
 * @internal
 */
#[CoversClass(Routes::class)]
final class RoutesTest extends TestCase
{
	/**
	 * Test that the administration's own path is what the routes are grouped under.
	 *
	 * The group prefix and the value `isAdministration()` compares a scope
	 * against are one and the same — the contract documents `getPath()` as
	 * feeding the group — so registering under anything else would separate
	 * where the routes live from the answer to "is this the administration?".
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
	 * Test that the group callback is given a router to register routes with.
	 *
	 * The routes themselves live inside the callback, so a callback invoked
	 * with anything other than the in-scope router would fail at registration
	 * time rather than being caught here.
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

						// Run it against a router mock: the callback's own
						// body is where routes would be registered, and the
						// signature it is invoked with is the contract. The
						// dashboard is mapped at the group root, so the route
						// lands at the administration path itself.
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
