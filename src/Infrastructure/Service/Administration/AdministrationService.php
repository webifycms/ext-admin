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

use Webify\Base\Domain\Service\Administration\AdministrationServiceInterface;
use Webify\Base\Domain\Service\Application\ApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;

/**
 * Administration service class.
 *
 * TODO: Add support to the sub-domain if possible. The administration currently supports only sub-path url pattern.
 */
final class AdministrationService implements AdministrationServiceInterface
{
	private readonly string $path;

	private readonly string $url;

	private bool $inAdministration = false;

	/**
	 * The object constructor.
	 */
	public function __construct(
		ApplicationServiceInterface|WebApplicationServiceInterface $appService,
		public readonly AdministrationMenuService $menuService
	) {
		$this->path   = $appService->getAdministrationPath();
		$this->url    = $appService->getApplication()->getUrlManager()->createAbsoluteUrl('/' . $this->path);
		$requestedUrl = ltrim($appService->getApplication()->getRequest()->url, '/');

		if (str_contains($requestedUrl, $this->path)) {
			$this->inAdministration = true;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getUrl(): string
	{
		return $this->url;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setMenuItems(array $items): void
	{
		$this->menuService->addItems($items);
	}

	/**
	 * {@inheritDoc}
	 */
	public function inAdministration(): bool
	{
		return $this->inAdministration;
	}
}
