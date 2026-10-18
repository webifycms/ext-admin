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

namespace Webify\Admin\Domain\Exception;

use InvalidArgumentException;
use Webify\Base\Contract\Exception\TranslatableExceptionInterface;
use Webify\Base\Contract\Translation\ExceptionTranslation;

use function sprintf;

/**
 * Exception thrown when the configured administration location is empty or invalid.
 */
final class InvalidAdminLocationException extends InvalidArgumentException implements TranslatableExceptionInterface
{
	/**
	 * Private constructor enforces the use of the factory methods to initiate this exception.
	 *
	 * @param ExceptionTranslation $translation the translation object for this exception
	 * @param string               $message     the exception message (optional)
	 */
	private function __construct(
		public readonly ExceptionTranslation $translation,
		string $message = ''
	) {
		parent::__construct($message);
	}

	/**
	 * Factory method to initiate when the administration path is empty,
	 * which would collide with the site root.
	 */
	public static function forEmpty(): self
	{
		return new self(
			new ExceptionTranslation(
				'admin.location',
				'admin_location_is_empty',
			),
			'Administration location must not be empty.'
		);
	}

	/**
	 * Factory method to initiate when the administration location type is invalid.
	 *
	 * @param string $type the invalid administration location
	 */
	public static function forInvalidType(string $type): self
	{
		return new self(
			new ExceptionTranslation(
				'admin.location',
				'admin_location_type_is_invalid',
				['type' => $type]
			),
			sprintf(
				'Administration location type "%s" is invalid.',
				$type
			)
		);
	}

	/**
	 * Factory method to initiate when the administration host contains unsupported characters.
	 *
	 * @param string $host the invalid administration host
	 */
	public static function forInvalidHost(string $host): self
	{
		return new self(
			new ExceptionTranslation(
				'admin.location',
				'admin_location_host_is_invalid',
				['host' => $host]
			),
			sprintf(
				'Administration host "%s" is invalid.',
				$host
			)
		);
	}

	/**
	 * Factory method to initiate when the administration path contains unsupported characters.
	 *
	 * @param string $path the invalid administration path
	 */
	public static function forInvalidPath(string $path): self
	{
		return new self(
			new ExceptionTranslation(
				'admin.location',
				'admin_location_path_is_invalid',
				['path' => $path]
			),
			sprintf(
				'Administration path "%s" is invalid, only letters, numbers, hyphens, 
				underscores and "/" separators are allowed.',
				$path
			)
		);
	}
}
