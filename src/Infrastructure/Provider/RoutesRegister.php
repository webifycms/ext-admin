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

namespace Webify\Admin\Infrastructure\Provider;

use Psr\Container\ContainerInterface;
use Webify\Admin\Infrastructure\Presentation\Http\Routes;
use Webify\Base\Infrastructure\Contract\{
	AdministrationInterface,
	BootstrapServiceProviderInterface,
};
use Webify\Base\Infrastructure\Contract\Routing\RouterInterface;

/**
 * Description.
 */
final class RoutesRegister implements BootstrapServiceProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function bootstrap(ContainerInterface $container): void
	{
		/** @var AdministrationInterface $administration */
		$administration = $container->get(AdministrationInterface::class);

		/** @var RouterInterface $router */
		$router         = $container->get(RouterInterface::class);

		new Routes($administration)->register($router);
	}
}
