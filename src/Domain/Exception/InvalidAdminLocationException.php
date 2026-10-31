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
 * Stopped because the administration's address is missing or has characters
 * that cannot appear in a web address.
 */
final class InvalidAdminLocationException extends InvalidArgumentException implements TranslatableExceptionInterface
{
	/**
	 * @param ExceptionTranslation $translation the message to show in the user's language
	 * @param string               $message     a message for logs
	 */
	private function __construct(
		public readonly ExceptionTranslation $translation,
		string $message = ''
	) {
		parent::__construct($message);
	}

	/**
	 * The administration has no address at all, which would put it on the
	 * site's home page and cover every request.
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
	 * The value given for the location type was not a string.
	 *
	 * @param string $type the kind of value that was given
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
	 * The host name has a character or shape that cannot be used.
	 *
	 * @param string $host the host name that was given
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
	 * The path has a character or shape that cannot be used.
	 *
	 * @param string $path the path that was given
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
