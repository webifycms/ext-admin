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

namespace Webify\Admin\Infrastructure\Service;

use Webify\Admin\Domain\ValueObject\AdminLocation;
use Webify\Base\Infrastructure\Contract\{
	AdministrationInterface,
	CurrentRequestInterface,
	UrlGeneratorInterface,
	UrlType,
};

use function mb_strtolower;
use function str_starts_with;

/**
 * The implementation of administration service.
 *
 * The service answers two questions that have to agree with each other: where
 * the administration's routes are registered (`getPath()`, fed to the router's
 * group), and whether those are the routes serving the request
 * (`isAdministration()`, reading the group scope the router publishes while
 * that group runs). Both are derived from one configured location, so a
 * configuration change moves them together or not at all.
 */
final readonly class Administration implements AdministrationInterface
{
	/**
	 * The constructor.
	 */
	public function __construct(
		private AdminLocation $location,
		private UrlGeneratorInterface $urlGenerator,
		private CurrentRequestInterface $currentRequest,
	) {}

	/**
	 * {@inheritDoc}
	 */
	public function isAdministration(): bool
	{
		$url = $this->currentRequest->current()?->getUri();

		if (null === $url) {
			return false;
		}

		return $this->matchLocation($url->getHost(), $url->getPath());
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPathPrefix(): string
	{
		return '' === $this->location->path ? '' : '/' . $this->location->path;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPath(): string
	{
		return $this->location->path;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUrl(UrlType $type = UrlType::Relative): string
	{
		if ($this->location->hasHost()) {
			// URL generator won't support for different host, by concatenating
			return $this->location->host . $this->getPathPrefix();
		}

		// Through the URL generator rather than by concatenating the base URL:
		// that is what honours `$type`, and it is what keeps the administration
		// URL to the same rules as every other URL the application builds.
		return $this->urlGenerator->path($this->getPathPrefix(), [], $type);
	}

	/**
	 * Match admin location with the current host and path.
	 */
	private function matchLocation(string $host, string $path): bool
	{
		if ($this->location->hasHost() && mb_strtolower($host) !== $this->location->host) {
			return false;
		}

		if ('' === $this->location->path) {
			return true;
		}

		$path = mb_strtolower('/' . trim($path, '/'));

		return $this->getPathPrefix() === $path || str_starts_with($path, $this->getPathPrefix() . '/');
	}
}
