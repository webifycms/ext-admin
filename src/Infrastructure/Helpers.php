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

namespace Webify\Admin\Infrastructure;

use Webify\Admin\Domain\Service\Administration\AdministrationServiceInterface;

use function Webify\Base\Infrastructure\dependency;

if (!\function_exists('administration')) {
	/**
	 * Returns the administration service instance.
	 */
	function administration(): AdministrationServiceInterface
	{
		return dependency()->getContainer()->get(AdministrationServiceInterface::class);
	}
}

if (!\function_exists('in_administration')) {
	/**
	 * Check weather in administration.
	 */
	function in_administration(): bool
	{
		return administration()->inAdministration();
	}
}

if (!\function_exists('administration_path')) {
	/**
	 * Returns the administration path.
	 */
	function administration_path(): string
	{
		return administration()->getPath();
	}
}

if (!\function_exists('administration_url')) {
	/**
	 * Returns the administration url.
	 */
	function administration_url(): string
	{
		return administration()->getUrl();
	}
}
