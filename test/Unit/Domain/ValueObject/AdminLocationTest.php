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

namespace Webify\Admin\Test\Unit\Domain\ValueObject;

use PHPUnit\Framework\Attributes\{CoversClass, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Webify\Admin\Domain\Exception\InvalidAdminLocationException;
use Webify\Admin\Domain\ValueObject\AdminLocation;

/**
 * Proves the address of the administration is kept the same way every time.
 *
 * Small differences in writing the address must lead to the same place.
 *
 * @internal
 */
#[CoversClass(AdminLocation::class)]
#[UsesClass(InvalidAdminLocationException::class)]
final class AdminLocationTest extends TestCase
{
	/**
	 * The path is made lower case and leading and trailing slashes and
	 * spaces are removed.
	 */
	#[Test]
	public function testCreateNormalizesThePath(): void
	{
		self::assertSame('admin', AdminLocation::create(null, '/Admin/')->path);
		self::assertSame('admin', AdminLocation::create(null, ' admin ')->path);
		self::assertSame('admin', AdminLocation::create(null, 'admin')->path);
	}

	/**
	 * The host is made lower case and spaces are removed, so addresses
	 * written differently still match each other.
	 */
	#[Test]
	public function testCreateNormalizesTheHost(): void
	{
		$location = AdminLocation::create(' Example.TEST ', 'admin');

		self::assertSame('example.test', $location->host);
		self::assertTrue($location->hasHost());
	}

	/**
	 * A location given only a path has no host.
	 */
	#[Test]
	public function testAPathOnlyLocationHasNoHost(): void
	{
		$location = AdminLocation::create(null, 'admin');

		self::assertNull($location->host);
		self::assertFalse($location->hasHost());
	}

	/**
	 * A location given only a host keeps an empty path. The only time an
	 * empty path is allowed is when there is a host to put the administration
	 * at.
	 */
	#[Test]
	public function testAHostOnlyLocationKeepsAnEmptyPath(): void
	{
		$location = AdminLocation::create('admin.example.test', '');

		self::assertSame('', $location->path);
		self::assertSame('admin.example.test', $location->host);
	}

	/**
	 * Leaving out both the host and the path would put the administration on
	 * the home address and cover every request, so it is refused.
	 */
	#[Test]
	public function testALocationWithoutHostOrPathIsRefused(): void
	{
		$this->expectException(InvalidAdminLocationException::class);

		AdminLocation::create(null, null);
	}

	/**
	 * A host with a space in it is refused.
	 */
	#[Test]
	public function testAHostWithUnsupportedCharactersIsRefused(): void
	{
		$this->expectException(InvalidAdminLocationException::class);

		AdminLocation::create('exa mple.test', 'admin');
	}

	/**
	 * A path with a space in it is refused. Only letters, numbers, hyphens,
	 * underscores and slashes are allowed, because anything else would not
	 * match a request path.
	 */
	#[Test]
	public function testAPathOutsideTheAllowedFormatIsRefused(): void
	{
		$this->expectException(InvalidAdminLocationException::class);

		AdminLocation::create(null, 'admin page');
	}

	/**
	 * Two locations are the same if their host and path are the same, even
	 * if written with different spacing or capital letters.
	 */
	#[Test]
	public function testEqualsComparesHostAndPath(): void
	{
		$location = AdminLocation::create('example.test', 'admin');

		self::assertTrue($location->equals(AdminLocation::create(' Example.TEST ', '/admin/')));
		self::assertFalse($location->equals(AdminLocation::create('example.test', 'panel')));
		self::assertFalse($location->equals(AdminLocation::create('other.test', 'admin')));
	}
}
