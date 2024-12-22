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

namespace Webify\Admin\Infrastructure\Service\Administration;

use Webify\Admin\Domain\Service\Administration\AdministrationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;

/**
 * This class provides functionality to manage the administration section of an application.
 * It determines the administration path and URL, and checks if the application is currently
 * in the administration context.
 */
final class AdministrationService implements AdministrationServiceInterface
{
	private readonly string $path;

	private readonly string $url;

	private bool $inAdministration = false;

	/**
	 * The class constructor.
	 */
	public function __construct(
		WebApplicationServiceInterface $appService
	) {
		$this->path   = $appService->getAdministrationPath();
		$this->url    = $appService->getApplication()->getUrlManager()->createAbsoluteUrl('/' . $this->path);
		$requestedUrl = ltrim($appService->getApplication()->getRequest()->url, '/');

		if (str_contains($requestedUrl, $this->path)) {
			$this->inAdministration = true;
		}
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function getUrl(): string
	{
		return $this->url;
	}

	public function inAdministration(): bool
	{
		return $this->inAdministration;
	}
}
