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

namespace Webify\Admin\Domain\ValueObject;

use Webify\Admin\Domain\Exception\InvalidAdminLocationException;

use function mb_strtolower;
use function preg_match;
use function trim;

/**
 * The administration location value object.
 */
final readonly class AdminLocation
{
	/**
	 * Allowed host format.
	 */
	private const string HOST_FORMAT = '/^(?=.{1,253}$)(?!-)[a-z0-9-]{1,63}(?<!-)(?:\.(?!-)[a-z0-9-]{1,63}(?<!-))*$/';

	/**
	 * Allowed path format: one or more segments of letters, numbers, hyphens and underscores.
	 */
	private const string PATH_FORMAT = '/^[a-z0-9_-]+(?:\/[a-z0-9_-]+)*$/';

	/**
	 * The private constructor enforces to use factory methods to initiate this object.
	 *
	 * @param null|string $host the host can be null, when the location is only path basis
	 * @param string      $path when the host is available, the path can be empty
	 */
	private function __construct(
		public ?string $host,
		public string $path
	) {}

	/**
	 * Factory method to create the object from a raw value.
	 *
	 * @throws InvalidAdminLocationException if the value is empty or has an invalid format
	 */
	public static function create(?string $host, ?string $path): self
	{
		$host = null === $host ? '' : mb_strtolower(trim($host));
		$path = trim(mb_strtolower($path ?? ''), " \t\n\r\0\x0B/");

		if ('' === $host && '' === $path) {
			throw InvalidAdminLocationException::forEmpty();
		}

		if ('' !== $host && 1 !== preg_match(self::HOST_FORMAT, $host)) {
			throw InvalidAdminLocationException::forInvalidHost($host);
		}

		if ('' !== $path && 1 !== preg_match(self::PATH_FORMAT, $path)) {
			throw InvalidAdminLocationException::forInvalidPath($path);
		}

		return new self('' === $host ? null : $host, $path);
	}

	/**
	 * Checks weather has host.
	 */
	public function hasHost(): bool
	{
		return null !== $this->host;
	}

	/**
	 * Equality check based on the value.
	 */
	public function equals(self $other): bool
	{
		return $this->host === $other->host
			&& $this->path === $other->path;
	}
}
