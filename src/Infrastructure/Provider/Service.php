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

use Webify\Admin\Infrastructure\Container\ServiceDefinitions;
use Webify\Base\Infrastructure\Contract\ServiceProviderInterface;

/**
 * Description.
 */
final readonly class Service implements ServiceProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getDefinitions(): array
	{
		return [
			...ServiceDefinitions::getDefinitions(),
		];
	}
}
