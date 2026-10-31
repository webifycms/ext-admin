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

namespace Webify\Admin\Test\Unit\Infrastructure\Service;

use PHPUnit\Framework\Attributes\{CoversClass, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\{ServerRequestInterface, UriInterface};
use Webify\Admin\Domain\ValueObject\AdminLocation;
use Webify\Admin\Infrastructure\Service\Administration;
use Webify\Base\Infrastructure\Contract\Http\CurrentRequestInterface;
use Webify\Base\Infrastructure\Contract\Url\{UrlBaseInterface, UrlGeneratorInterface, UrlType};

/**
 * Proves the administration answers where it lives and which requests are
 * for it.
 *
 * The check and the addresses are both derived from one location, so the
 * tests keep them together.
 *
 * @internal
 */
#[CoversClass(Administration::class)]
#[UsesClass(AdminLocation::class)]
final class AdministrationTest extends TestCase
{
	/**
	 * Outside the web — a console command or a worker sending mail — there
	 * is no request, so nothing can be the administration.
	 */
	#[Test]
	public function testAWeblessRunIsNeverTheAdministration(): void
	{
		$administration = self::administration(
			AdminLocation::create(null, 'admin'),
			self::weblessRequest()
		);

		self::assertFalse($administration->isAdministration());
	}

	/**
	 * A location with only a path checks paths only: the request may come
	 * from any host that serves the application.
	 */
	#[Test]
	public function testTheConfiguredPathIsTheAdministration(): void
	{
		$administration = self::administration(
			AdminLocation::create(null, 'admin'),
			self::requestAt('www.example.com', '/admin')
		);

		self::assertTrue($administration->isAdministration());
	}

	/**
	 * Anything below the administration path belongs to the administration.
	 */
	#[Test]
	public function testPathsUnderTheAdministrationAreStillIt(): void
	{
		$administration = self::administration(
			AdminLocation::create(null, 'admin'),
			self::requestAt('www.example.com', '/admin/dashboard')
		);

		self::assertTrue($administration->isAdministration());
	}

	/**
	 * A path that only resembles the administration path must not pass: a
	 * route prefix match keeps `/administrator` out.
	 */
	#[Test]
	public function testPathsOutsideTheAdministrationAreNotIt(): void
	{
		$administration = self::administration(
			AdminLocation::create(null, 'admin'),
			self::requestAt('www.example.com', '/dashboard')
		);

		self::assertFalse($administration->isAdministration());

		$administration = self::administration(
			AdminLocation::create(null, 'admin'),
			self::requestAt('www.example.com', '/administrator')
		);

		self::assertFalse($administration->isAdministration());
	}

	/**
	 * The address of the administration is matched the way it is written,
	 * whatever letter case the request uses.
	 */
	#[Test]
	public function testPathMatchingIgnoresLetterCase(): void
	{
		$administration = self::administration(
			AdminLocation::create(null, 'admin'),
			self::requestAt('www.example.com', '/Admin/Dashboard/')
		);

		self::assertTrue($administration->isAdministration());
	}

	/**
	 * With no path, the whole host is the administration, and any page on
	 * it belongs to it.
	 */
	#[Test]
	public function testTheWholeHostIsTheAdministrationWhenOnlyAHostIsSet(): void
	{
		$administration = self::administration(
			AdminLocation::create('admin.example.com', null),
			self::requestAt('admin.example.com', '/anything')
		);

		self::assertTrue($administration->isAdministration());
	}

	/**
	 * A host set for the administration must be the one the request is
	 * delivered to; any other host is not it.
	 */
	#[Test]
	public function testARequestToAnotherHostIsNotTheAdministration(): void
	{
		$administration = self::administration(
			AdminLocation::create('admin.example.com', null),
			self::requestAt('www.example.com', '/admin')
		);

		self::assertFalse($administration->isAdministration());
	}

	/**
	 * When the administration sets both a host and a path, a request on the
	 * right host but under a different path is not it.
	 */
	#[Test]
	public function testHostAndPathMustBothMatch(): void
	{
		$administration = self::administration(
			AdminLocation::create('admin.example.com', 'admin'),
			self::requestAt('admin.example.com', '/dashboard')
		);

		self::assertFalse($administration->isAdministration());
	}

	/**
	 * The host of the administration is matched the way it is written,
	 * whatever letter case the request uses.
	 */
	#[Test]
	public function testHostMatchingIgnoresLetterCase(): void
	{
		$administration = self::administration(
			AdminLocation::create('admin.example.com', null),
			self::requestAt('ADMIN.EXAMPLE.com', '/anything')
		);

		self::assertTrue($administration->isAdministration());
	}

	/**
	 * The routes are grouped under a path with a leading slash.
	 */
	#[Test]
	public function testTheAdministrationPathIsPrefixedWithASlash(): void
	{
		self::assertSame(
			'/admin',
			self::administration(AdminLocation::create(null, 'admin'))->getPathPrefix()
		);
	}

	/**
	 * A location with only a host has no path, so there is nothing to
	 * prefix the routes with.
	 */
	#[Test]
	public function testAHostOnlyLocationHasNoPathPrefix(): void
	{
		self::assertSame(
			'',
			self::administration(AdminLocation::create('admin.example.com', null))->getPathPrefix()
		);
	}

	/**
	 * The path is the one the routes are grouped under, without slashes.
	 */
	#[Test]
	public function testThePathIsTheConfiguredPath(): void
	{
		$administration = self::administration(AdminLocation::create(null, 'admin'));

		self::assertSame('admin', $administration->getPath());
		self::assertSame(
			'',
			self::administration(AdminLocation::create('admin.example.com', null))->getPath()
		);
	}

	/**
	 * A relative URL cannot name a host, so even a location with its own
	 * host is written as a plain path.
	 */
	#[Test]
	public function testRelativeUrlsAreBuiltFromThePathAlone(): void
	{
		$urlGenerator = self::createMock(UrlGeneratorInterface::class);

		$urlGenerator->expects(self::once())
			->method('path')
			->with('/admin', [], UrlType::Relative)
			->willReturn('/admin')
		;

		$administration = self::administration(
			AdminLocation::create('admin.example.com', 'admin'),
			urlGenerator: $urlGenerator
		);

		self::assertSame('/admin', $administration->getUrl());
	}

	/**
	 * Without a host there is nothing to add to the address, so absolute
	 * and network path URLs are built by the generator like relative ones.
	 */
	#[Test]
	public function testUrlsWithoutAHostFallBackToPathGeneration(): void
	{
		$urlGenerator = self::createMock(UrlGeneratorInterface::class);

		$urlGenerator->expects(self::exactly(2))
			->method('path')
			->willReturnCallback(
				function (string $path, array $query, UrlType $type): string {
					self::assertSame('/admin', $path);
					self::assertSame([], $query);
					self::assertContains($type, [UrlType::Absolute, UrlType::NetworkPath]);

					return $path;
				}
			)
		;

		$administration = self::administration(
			AdminLocation::create(null, 'admin'),
			urlGenerator: $urlGenerator
		);

		self::assertSame('/admin', $administration->getUrl(UrlType::Absolute));
		self::assertSame('/admin', $administration->getUrl(UrlType::NetworkPath));
	}

	/**
	 * When the location names a scheme, that one is used and the site's
	 * base address is never asked for one.
	 */
	#[Test]
	public function testAbsoluteUrlsUseTheConfiguredScheme(): void
	{
		$baseUrl = self::createMock(UrlBaseInterface::class);

		$baseUrl->expects(self::never())->method('getScheme');

		$administration = self::administration(
			AdminLocation::create('admin.example.com', 'admin', 'http'),
			baseUrl: $baseUrl
		);

		self::assertSame('http://admin.example.com/admin', $administration->getUrl(UrlType::Absolute));
	}

	/**
	 * Without its own scheme, the address uses the scheme the site itself
	 * is reached at.
	 */
	#[Test]
	public function testAbsoluteUrlsFallBackToTheSiteScheme(): void
	{
		$baseUrl = self::createStub(UrlBaseInterface::class);

		$baseUrl->method('getScheme')->willReturn('https');

		$administration = self::administration(
			AdminLocation::create('admin.example.com', 'admin'),
			baseUrl: $baseUrl
		);

		self::assertSame('https://admin.example.com/admin', $administration->getUrl(UrlType::Absolute));
	}

	/**
	 * A port set for the administration is part of the address.
	 */
	#[Test]
	public function testAbsoluteUrlsIncludeTheConfiguredPort(): void
	{
		$baseUrl = self::createStub(UrlBaseInterface::class);

		$baseUrl->method('getScheme')->willReturn('https');

		$administration = self::administration(
			AdminLocation::create('admin.example.com', 'admin', null, 8080),
			baseUrl: $baseUrl
		);

		self::assertSame(
			'https://admin.example.com:8080/admin',
			$administration->getUrl(UrlType::Absolute)
		);
	}

	/**
	 * A network path URL names no scheme at all, so the site's base address
	 * is never asked for one.
	 */
	#[Test]
	public function testNetworkPathUrlsCarryNoScheme(): void
	{
		$baseUrl = self::createMock(UrlBaseInterface::class);
		$baseUrl->expects(self::never())->method('getScheme');

		$administration = self::administration(
			AdminLocation::create('admin.example.com', 'admin', 'http', 8080),
			baseUrl: $baseUrl
		);

		self::assertSame('//admin.example.com:8080/admin', $administration->getUrl(UrlType::NetworkPath));
	}

	/**
	 * A location with only a host has no path, so its address ends at the
	 * authority.
	 */
	#[Test]
	public function testAHostOnlyLocationBuildsUrlsWithoutAPath(): void
	{
		$baseUrl = self::createStub(UrlBaseInterface::class);

		$baseUrl->method('getScheme')->willReturn('https');

		$administration = self::administration(
			AdminLocation::create('admin.example.com', null),
			baseUrl: $baseUrl
		);

		self::assertSame('https://admin.example.com', $administration->getUrl(UrlType::Absolute));
	}

	/**
	 * Builds the service from a location, the request to check, and the
	 * collaborators the URLs are built with.
	 */
	private static function administration(
		AdminLocation $location,
		?CurrentRequestInterface $currentRequest = null,
		?UrlGeneratorInterface $urlGenerator = null,
		?UrlBaseInterface $baseUrl = null
	): Administration {
		return new Administration(
			$location,
			$urlGenerator ?? self::createStub(UrlGeneratorInterface::class),
			$currentRequest ?? self::requestAt('www.example.com', '/admin'),
			$baseUrl ?? self::createStub(UrlBaseInterface::class)
		);
	}

	/**
	 * A web request delivered to the given host and path.
	 */
	private static function requestAt(string $host, string $path): CurrentRequestInterface
	{
		$uri = self::createStub(UriInterface::class);

		$uri->method('getHost')->willReturn($host);
		$uri->method('getPath')->willReturn($path);

		$request = self::createStub(ServerRequestInterface::class);

		$request->method('getUri')->willReturn($uri);

		$currentRequest = self::createStub(CurrentRequestInterface::class);

		$currentRequest->method('current')->willReturn($request);

		return $currentRequest;
	}

	/**
	 * No web request at all: a console command or a worker.
	 */
	private static function weblessRequest(): CurrentRequestInterface
	{
		$currentRequest = self::createStub(CurrentRequestInterface::class);

		$currentRequest->method('current')->willReturn(null);

		return $currentRequest;
	}
}
