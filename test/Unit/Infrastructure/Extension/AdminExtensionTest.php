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

namespace Webify\Admin\Test\Unit\Infrastructure\Extension;

use PHPUnit\Framework\Attributes\{CoversClass, CoversFunction, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Webify\Admin\Infrastructure\Extension\AdminExtension;
use Webify\Admin\Infrastructure\Provider\{RoutesRegister, Service};
use Webify\Base\Infrastructure\Contract\Extension\ExtensionInterface;
use Webify\Base\Infrastructure\Contract\Provider\{BootstrapServiceInterface, ServiceInterface};

use function dirname;
use function sprintf;

/**
 * Proves the extension gives the application the pieces it needs.
 *
 * @internal
 */
#[CoversClass(AdminExtension::class)]
#[UsesClass(Service::class)]
#[UsesClass(RoutesRegister::class)]
#[CoversFunction('dirname')]
#[CoversMethod(AdminExtension::class, 'getId')]
#[CoversMethod(AdminExtension::class, 'getSlug')]
#[CoversMethod(AdminExtension::class, 'getName')]
#[CoversMethod(AdminExtension::class, 'getVersion')]
#[CoversMethod(AdminExtension::class, 'getTemplatePath')]
#[CoversMethod(AdminExtension::class, 'getProviders')]
#[CoversMethod(AdminExtension::class, 'getAssetsProvider')]
final class AdminExtensionTest extends TestCase
{
	/**
	 * The extension being tested.
	 */
	private AdminExtension $extension;

	/**
	 * {@inheritDoc}
	 */
	protected function setUp(): void
	{
		$this->extension = new AdminExtension();
	}

	/**
	 * The extension must fit the contract the application looks for.
	 */
	#[Test]
	public function testImplementsExtensionInterface(): void
	{
		self::assertInstanceOf(ExtensionInterface::class, $this->extension);
	}

	/**
	 * The identity must stay the same once other pieces depend on it.
	 *
	 * @see ExtensionInterface::getId()
	 */
	#[Test]
	public function testGetId(): void
	{
		self::assertSame('webifycms-admin', $this->extension->getId());
	}

	/**
	 * The address label mirrors the identity for now.
	 *
	 * @see ExtensionInterface::getSlug()
	 */
	#[Test]
	public function testGetSlug(): void
	{
		self::assertSame('webifycms-admin', $this->extension->getSlug());
	}

	/**
	 * The name shown to a person.
	 *
	 * @see ExtensionInterface::getName()
	 */
	#[Test]
	public function testGetName(): void
	{
		self::assertSame('WebifyCMS Admin Extension', $this->extension->getName());
	}

	/**
	 * The version has to be changed when the package is released.
	 *
	 * @see ExtensionInterface::getVersion()
	 */
	#[Test]
	public function testGetVersion(): void
	{
		self::assertSame('0.2.0-beta', $this->extension->getVersion());
	}

	/**
	 * The templates live in the `templates` folder next to `src`.
	 *
	 * @see ExtensionInterface::getTemplatePath()
	 */
	#[Test]
	public function testGetTemplatePathPointsAtThePackageTemplatesDirectory(): void
	{
		$path        = $this->extension->getTemplatePath()->toNative();
		$packageRoot = dirname(__DIR__, 4);

		self::assertSame(
			$packageRoot . DIRECTORY_SEPARATOR . 'templates',
			$path,
			'the template path must resolve to <package root>/templates'
		);
		self::assertDirectoryExists($path);
	}

	/**
	 * Two providers are given: one to add pieces to the container, one to
	 * add routes when the application starts.
	 *
	 * @see ExtensionInterface::getProviders()
	 */
	#[Test]
	public function testGetProvidersYieldsTheServiceAndRouteProviders(): void
	{
		$providers = $this->extension->getProviders();

		self::assertCount(2, $providers);
		self::assertInstanceOf(Service::class, $providers[0]);
		self::assertInstanceOf(RoutesRegister::class, $providers[1]);
	}

	/**
	 * Each provider must be one of the kinds the application expects.
	 *
	 * @see ExtensionInterface::getProviders()
	 */
	#[Test]
	public function testGetProvidersOnlyYieldsProviderInstances(): void
	{
		foreach ($this->extension->getProviders() as $provider) {
			self::assertTrue(
				$provider instanceof ServiceInterface
				|| $provider instanceof BootstrapServiceInterface,
				sprintf('Unexpected provider of type %s.', $provider::class)
			);
		}
	}

	/**
	 * The container pieces must actually come from the providers.
	 *
	 * @see ExtensionInterface::getProviders()
	 */
	#[Test]
	public function testTheProvidersContributeDefinitions(): void
	{
		$definitions = [];

		foreach ($this->extension->getProviders() as $provider) {
			if (!$provider instanceof ServiceInterface) {
				continue;
			}

			$definitions += $provider->getDefinitions();
		}

		self::assertNotEmpty($definitions);
	}

	/**
	 * The extension has no separate assets provider — the theme provides the
	 * look and feel.
	 *
	 * @see ExtensionInterface::getAssetsProvider()
	 */
	#[Test]
	public function testGetAssetsProviderIsNull(): void
	{
		self::assertNull($this->extension->getAssetsProvider());
	}
}
