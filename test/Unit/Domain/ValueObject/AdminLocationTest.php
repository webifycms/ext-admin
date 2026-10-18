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
 * AdminLocationTest tests the administration location value object.
 *
 * The object owns every normalization the administration's route group and
 * its URL are built from, so `admin`, `/admin/` and ` /admin ` have to reach
 * one value here or they reach different route groups later.
 *
 * `create()` takes the host first and the path second, mirroring the value
 * object's own shape: the host is the optional constraint and the path is the
 * value that is always present.
 *
 * @internal
 */
#[CoversClass(AdminLocation::class)]
#[UsesClass(InvalidAdminLocationException::class)]
final class AdminLocationTest extends TestCase
{
	/**
	 * Test that the path arrives normalized: lowercase, without surrounding
	 * slashes or whitespace.
	 */
	#[Test]
	public function testCreateNormalizesThePath(): void
	{
		self::assertSame('admin', AdminLocation::create(null, '/Admin/')->path);
		self::assertSame('admin', AdminLocation::create(null, ' admin ')->path);
		self::assertSame('admin', AdminLocation::create(null, 'admin')->path);
	}

	/**
	 * Test that the host arrives lowercased and trimmed.
	 *
	 * Hosts are compared case-insensitively, so a location holding ` Example.TEST `
	 * would otherwise never equal one holding `example.test`.
	 */
	#[Test]
	public function testCreateNormalizesTheHost(): void
	{
		$location = AdminLocation::create(' Example.TEST ', 'admin');

		self::assertSame('example.test', $location->host);
		self::assertTrue($location->hasHost());
	}

	/**
	 * Test that a path-only location has no host.
	 */
	#[Test]
	public function testAPathOnlyLocationHasNoHost(): void
	{
		$location = AdminLocation::create(null, 'admin');

		self::assertNull($location->host);
		self::assertFalse($location->hasHost());
	}

	/**
	 * Test that a host-only location keeps an empty path.
	 *
	 * The path may be empty when the host is available — the administration
	 * then lives at that host's root — but only then.
	 */
	#[Test]
	public function testAHostOnlyLocationKeepsAnEmptyPath(): void
	{
		$location = AdminLocation::create('admin.example.test', '');

		self::assertSame('', $location->path);
		self::assertSame('admin.example.test', $location->host);
	}

	/**
	 * Test that a location with neither host nor path is refused.
	 *
	 * An empty location would place the administration on the site root and
	 * make every request the administration's.
	 */
	#[Test]
	public function testALocationWithoutHostOrPathIsRefused(): void
	{
		$this->expectException(InvalidAdminLocationException::class);

		AdminLocation::create(null, null);
	}

	/**
	 * Test that a host containing unsupported characters is refused.
	 */
	#[Test]
	public function testAHostWithUnsupportedCharactersIsRefused(): void
	{
		$this->expectException(InvalidAdminLocationException::class);

		AdminLocation::create('exa mple.test', 'admin');
	}

	/**
	 * Test that a path outside the allowed format is refused.
	 *
	 * Only letters, numbers, hyphens, underscores and `/` separators survive —
	 * anything else would never match a request path on the way in.
	 */
	#[Test]
	public function testAPathOutsideTheAllowedFormatIsRefused(): void
	{
		$this->expectException(InvalidAdminLocationException::class);

		AdminLocation::create(null, 'admin page');
	}

	/**
	 * Test that equality is based on the value, not on identity.
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
