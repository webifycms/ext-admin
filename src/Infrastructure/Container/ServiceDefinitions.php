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

use Webify\Admin\Domain\Exception\InvalidAdminLocationException;
use Webify\Admin\Domain\ValueObject\AdminLocation;
use Webify\Admin\Infrastructure\Service\Administration;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\{AdministrationInterface, DefinitionsInterface};

use function DI\{autowire, factory};
use function get_debug_type;

/**
 * Service definitions.
 */
final readonly class ServiceDefinitions implements DefinitionsInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getDefinitions(): array
	{
		return [
			// -------------------------------------------------------------------------
			// Administration path and area resolver
			// The path is validated lazily on first use, so a bad `adminPath` fails there.
			// -------------------------------------------------------------------------
			AdminLocation::class                            => factory(
				static function (ConfigInterface $config): AdminLocation {
					/** @var string $host */
					$host = $config->get('adminHost', '');

					/** @var string $path */
					$path = $config->get('adminPath', '');

					foreach ([$host, $path] as $value) {
						if (!is_string($value)) {
							throw InvalidAdminLocationException::forInvalidType(get_debug_type($value));
						}
					}

					if ('' === $host && '' === $path) {
						$host = null;
						$path = AdministrationInterface::DEFAULT_PATH;
					}

					return AdminLocation::create($host, $path);
				}
			),
			// -------------------------------------------------------------------------
			// Autowired rather than `create()`: the service takes three
			// container services through its constructor, and only autowiring
			// resolves a constructor's parameters by type.
			// -------------------------------------------------------------------------
			AdministrationInterface::class                  => autowire(Administration::class),
		];
	}
}
