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
 * Holds the address where the administration lives.
 *
 * It keeps the host, optional scheme and port, and path in one place,
 * normalizes them the same way every time, and refuses a location that
 * would put the administration on the site's home address.
 */
final readonly class AdminLocation
{
	/**
	 * The pattern a host name must match.
	 */
	private const string HOST_FORMAT = '/^(?=.{1,253}$)(?!-)[a-z0-9-]{1,63}(?<!-)(?:\.(?!-)[a-z0-9-]{1,63}(?<!-))*$/';

	/**
	 * The pattern a path must match.
	 */
	private const string PATH_FORMAT = '/^[a-z0-9_-]+(?:\/[a-z0-9_-]+)*$/';

	/**
	 * The pattern a scheme must match.
	 */
	private const string SCHEME_FORMAT = '/^[a-z][a-z0-9+.-]*$/';

	/**
	 * @param null|string $host   the host name, or null if the location uses a path only
	 * @param string      $path   the path, or an empty string if the location uses a host only
	 * @param null|string $scheme the scheme, or null to use the site's default
	 * @param null|int    $port   the port, or null for the default port
	 */
	private function __construct(
		public ?string $host,
		public string $path,
		public ?string $scheme = null,
		public ?int $port = null
	) {}

	/**
	 * Builds a location from the host, path, scheme and port values.
	 *
	 * Both host and path are made lower case and trimmed. The host is dropped
	 * if left empty. The path is dropped of leading and trailing slashes.
	 * Scheme is normalized to lower case when provided. Port must be between
	 * 1 and 65535. If nothing is left, the administration would be on the
	 * home address, so that is refused.
	 *
	 * @throws InvalidAdminLocationException if the location is empty or uses invalid characters
	 */
	public static function create(?string $host, ?string $path, ?string $scheme = null, ?int $port = null): self
	{
		$host   = null === $host ? '' : mb_strtolower(trim($host));
		$path   = trim(mb_strtolower($path ?? ''), " \t\n\r\0\x0B/");
		$scheme = null === $scheme ? null : mb_strtolower(trim($scheme));

		if ('' === $host && '' === $path) {
			throw InvalidAdminLocationException::forEmpty();
		}

		if ('' !== $host && 1 !== preg_match(self::HOST_FORMAT, $host)) {
			throw InvalidAdminLocationException::forInvalidHost($host);
		}

		if ('' !== $path && 1 !== preg_match(self::PATH_FORMAT, $path)) {
			throw InvalidAdminLocationException::forInvalidPath($path);
		}

		if (null !== $scheme && ('' === $scheme || 1 !== preg_match(self::SCHEME_FORMAT, $scheme))) {
			throw InvalidAdminLocationException::forInvalidType($scheme);
		}

		if (null !== $port && (1 > $port || 65535 < $port)) {
			throw InvalidAdminLocationException::forInvalidType((string) $port);
		}

		return new self('' === $host ? null : $host, $path, $scheme, $port);
	}

	/**
	 * Says whether the location uses a host name.
	 */
	public function hasHost(): bool
	{
		return null !== $this->host;
	}

	/**
	 * Says whether this location is the same as another.
	 */
	public function equals(self $other): bool
	{
		return $this->host === $other->host
			&& $this->path === $other->path
			&& $this->scheme === $other->scheme
			&& $this->port === $other->port;
	}
}
