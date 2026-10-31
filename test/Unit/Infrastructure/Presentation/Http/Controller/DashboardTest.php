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

namespace Webify\Admin\Test\Unit\Infrastructure\Presentation\Http\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\Attributes\{CoversClass, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Webify\Admin\Infrastructure\Presentation\Http\Controller\Dashboard;
use Webify\Base\Infrastructure\Contract\Template\TemplateViewInterface;
use Webify\Base\Infrastructure\Presentation\Http\Controller\Controller;

/**
 * Proves the dashboard page uses the right frame and returns a page.
 *
 * @internal
 */
#[CoversClass(Dashboard::class)]
#[UsesClass(Controller::class)]
#[UsesClass(Psr17Factory::class)]
final class DashboardTest extends TestCase
{
	/**
	 * The dashboard is wrapped in the theme's admin frame, not in the
	 * theme's public frame.
	 */
	#[Test]
	public function testInvokeRendersTheDashboardUnderTheNamedAdminLayout(): void
	{
		$factory = new Psr17Factory();

		$view = self::createMock(TemplateViewInterface::class);
		$view->expects(self::once())
			->method('render')
			->with(
				'dashboard/index',
				['currentPage' => 'dashboard'],
				'admin/layouts/main'
			)
			->willReturn('<main><h1>Dashboard</h1></main>')
		;

		$response = (new Dashboard($factory, $factory, $view))(
			self::createStub(ServerRequestInterface::class)
		);

		self::assertSame(200, $response->getStatusCode());
		self::assertSame('text/html; charset=utf-8', $response->getHeaderLine('Content-Type'));
		self::assertSame('<main><h1>Dashboard</h1></main>', (string) $response->getBody());
	}
}
