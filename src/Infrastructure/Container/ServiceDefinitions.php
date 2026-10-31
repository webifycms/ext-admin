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

namespace Webify\Admin\Infrastructure\Container;

use Webify\Admin\Domain\ValueObject\AdminLocation;
use Webify\Admin\Infrastructure\Service\Administration;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\Administration\AdministrationInterface;
use Webify\Base\Infrastructure\Contract\Container\DefinitionsInterface;

use function DI\{autowire, factory};

/**
 * Sets up the pieces needed to run the administration.
 */
final readonly class ServiceDefinitions implements DefinitionsInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getDefinitions(): array
	{
		return [
			// The address of the administration, read from settings and checked
			// the first time it is used.
			AdminLocation::class           => factory(
				static function (ConfigInterface $config): AdminLocation {
					/** @var null|string $host */
					$host = $config->get('adminHost');

					/** @var null|string $path */
					$path = $config->get('adminPath');

					/** @var null|string $scheme */
					$scheme = $config->get('adminScheme');

					/** @var null|int $port */
					$port = $config->get('adminPort');

					return AdminLocation::create($host, $path, $scheme, $port);
				}
			),
			// The service uses other pieces of the application, so it is let
			// the container build it with the things it needs.
			AdministrationInterface::class => autowire(Administration::class),
		];
	}
}
