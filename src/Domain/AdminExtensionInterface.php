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

namespace Webify\Admin\Domain;

use Webify\Base\Domain\ExtensionInterface;

interface AdminExtensionInterface extends ExtensionInterface
{
	/**
	 * The extension name.
	 */
	public const NAME = 'Admin';

	/**
	 * The extension version.
	 */
	public const VERSION = '0.0.1';

	/**
	 * The extension templates path.
	 */
	public const TEMPLATES_PATH = '@Admin/templates';

	/**
	 * The extension assets path.
	 */
	public const ASSETS_PATH = '@Admin/resources/assets';

	public static function getInstance(): self;
}
