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

namespace Webify\Admin\Test\Unit\Domain\Exception;

use PHPUnit\Framework\Attributes\{CoversClass, CoversFunction, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Webify\Admin\Domain\Exception\InvalidAdminLocationException;
use Webify\Base\Contract\Exception\TranslatableExceptionInterface;

/**
 * Proves the errors about the administration's address say what went wrong.
 *
 * @internal
 */
#[CoversClass(InvalidAdminLocationException::class)]
#[UsesClass(TranslatableExceptionInterface::class)]
#[CoversFunction('sprintf')]
final class InvalidAdminLocationExceptionTest extends TestCase
{
	#[Test]
	public function testImplementsTranslatableExceptionInterface(): void
	{
		self::assertInstanceOf(TranslatableExceptionInterface::class, InvalidAdminLocationException::forEmpty());
	}

	/**
	 * An empty location points to a specific problem and has no extra details.
	 */
	#[Test]
	public function testForEmptyExplainsItself(): void
	{
		$exception = InvalidAdminLocationException::forEmpty();

		self::assertSame('admin.location', $exception->translation->group);
		self::assertSame('admin_location_is_empty', $exception->translation->key);
		self::assertSame([], $exception->translation->params);
		self::assertSame('Administration location must not be empty.', $exception->getMessage());
	}

	/**
	 * A wrong type names the type that was given.
	 */
	#[Test]
	public function testForInvalidTypeNamesTheType(): void
	{
		$exception = InvalidAdminLocationException::forInvalidType('array');

		self::assertSame('admin_location_type_is_invalid', $exception->translation->key);
		self::assertSame(['type' => 'array'], $exception->translation->params);
		self::assertStringContainsString('array', $exception->getMessage());
	}

	/**
	 * A bad host names the host that was given.
	 */
	#[Test]
	public function testForInvalidHostNamesTheHost(): void
	{
		$exception = InvalidAdminLocationException::forInvalidHost('exa mple.test');

		self::assertSame('admin_location_host_is_invalid', $exception->translation->key);
		self::assertSame(['host' => 'exa mple.test'], $exception->translation->params);
		self::assertStringContainsString('exa mple.test', $exception->getMessage());
	}

	/**
	 * A bad path names the path that was given.
	 */
	#[Test]
	public function testForInvalidPathNamesThePath(): void
	{
		$exception = InvalidAdminLocationException::forInvalidPath('admin page');

		self::assertSame('admin_location_path_is_invalid', $exception->translation->key);
		self::assertSame(['path' => 'admin page'], $exception->translation->params);
		self::assertStringContainsString('admin page', $exception->getMessage());
	}
}
