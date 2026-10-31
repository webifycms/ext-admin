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
use Webify\Base\Infrastructure\Contract\Administration\AdministrationInterface;
use Webify\Base\Infrastructure\Contract\Http\CurrentRequestInterface;
use Webify\Base\Infrastructure\Contract\Url\{UrlBaseInterface, UrlGeneratorInterface, UrlType};

use function mb_strtolower;
use function sprintf;
use function str_starts_with;

/**
 * Answers whether a request is for the administration, and where the
 * administration is.
 *
 * The address of the administration (host and path) is set once and used for
 * both answers, so the routes and the check stay in step.
 */
final readonly class Administration implements AdministrationInterface
{
	public function __construct(
		private AdminLocation $location,
		private UrlGeneratorInterface $urlGenerator,
		private CurrentRequestInterface $currentRequest,
		private UrlBaseInterface $baseUrl,
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
	 * {@inheritDoc}
	 */
	public function getUrl(UrlType $type = UrlType::Relative): string
	{
		if (!$this->location->hasHost() || UrlType::Relative === $type) {
			// A relative URL cannot name a different host; fall back to path-based generation.
			return $this->urlGenerator->path($this->getPathPrefix(), [], $type);
		}

		// 2. Consolidate host and port logic
		$host      = $this->location->host;
		$port      = null !== $this->location->port ? ':' . $this->location->port : '';
		$authority = $host . $port;
		$path      = $this->getPathPrefix();

		// 3. Handle NetworkPath type, no scheme involved here
		if (UrlType::NetworkPath === $type) {
			return sprintf('//%s%s', $authority, $path);
		}

		// 4. Handle with optional scheme
		$scheme       = $this->location->scheme ?? $this->baseUrl->getScheme();
		$schemePrefix = is_string($scheme) && '' !== $scheme ? $scheme . '://' : '';

		return sprintf('%s%s%s', $schemePrefix, $authority, $path);
	}

	/**
	 * Checks if a host and path match the administration address.
	 *
	 * @return bool True if they match
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
