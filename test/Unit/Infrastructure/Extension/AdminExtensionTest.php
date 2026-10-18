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
use Webify\Base\Infrastructure\Contract\{
	BootstrapServiceProviderInterface,
	ExtensionInterface,
	ServiceProviderInterface,
};

use function dirname;
use function sprintf;

/**
 * AdminExtensionTest tests the extension entry point metadata.
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
	 * @var AdminExtension the admin extension instance
	 */
	private AdminExtension $extension;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void
	{
		$this->extension = new AdminExtension();
	}

	/**
	 * The extension must satisfy the ext-base contract it is discovered through.
	 */
	#[Test]
	public function testImplementsExtensionInterface(): void
	{
		self::assertInstanceOf(ExtensionInterface::class, $this->extension);
	}

	/**
	 * The id is the stable key the kernel discovers extensions by and resolves templates
	 * through, so it must not drift once anything depends on it.
	 *
	 * @see ExtensionInterface::getId()
	 */
	#[Test]
	public function testGetId(): void
	{
		self::assertSame('webifycms-admin', $this->extension->getId());
	}

	/**
	 * The slug is a URL-facing label rather than an identity, so it only mirrors the id here.
	 *
	 * @see ExtensionInterface::getSlug()
	 */
	#[Test]
	public function testGetSlug(): void
	{
		self::assertSame('webifycms-admin', $this->extension->getSlug());
	}

	/**
	 * The display name is what a human reads in the admin.
	 *
	 * @see ExtensionInterface::getName()
	 */
	#[Test]
	public function testGetName(): void
	{
		self::assertSame('WebifyCMS Admin Extension', $this->extension->getName());
	}

	/**
	 * The version is a hand-maintained release marker, so a release has to bump it in the
	 * same change as the package.
	 *
	 * @see ExtensionInterface::getVersion()
	 */
	#[Test]
	public function testGetVersion(): void
	{
		self::assertSame('0.2.0-beta', $this->extension->getVersion());
	}

	/**
	 * The extension lives in `src/Infrastructure/Extension`, so its root is three levels up
	 * and the templates directory sits beside `src`.
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
	 * The extension yields the provider that registers its definitions and the
	 * one that registers its routes, in that order.
	 *
	 * Both are needed: the first contributes the container bindings, the second
	 * runs at bootstrap to map the administration's routes.
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
	 * Whatever is returned must be usable by the container, so guard the shape of
	 * the list: every entry is either a definitions provider or a bootstrap
	 * provider — the two contracts the extension point accepts.
	 *
	 * @see ExtensionInterface::getProviders()
	 */
	#[Test]
	public function testGetProvidersOnlyYieldsProviderInstances(): void
	{
		foreach ($this->extension->getProviders() as $provider) {
			self::assertTrue(
				$provider instanceof ServiceProviderInterface
				|| $provider instanceof BootstrapServiceProviderInterface,
				sprintf('Unexpected provider of type %s.', $provider::class)
			);
		}
	}

	/**
	 * The provider's definitions are what the container actually receives, so a provider
	 * that returned nothing would pass every test above while registering no definitions.
	 *
	 * @see ExtensionInterface::getProviders()
	 */
	#[Test]
	public function testTheProvidersContributeDefinitions(): void
	{
		$definitions = [];

		foreach ($this->extension->getProviders() as $provider) {
			if (!$provider instanceof ServiceProviderInterface) {
				continue;
			}

			$definitions += $provider->getDefinitions();
		}

		self::assertNotEmpty($definitions);
	}

	/**
	 * The extension ships no assets of its own — front-end assets come from the theme — so
	 * the contract's "nothing to publish" case is the correct answer here.
	 *
	 * @see ExtensionInterface::getAssetsProvider()
	 */
	#[Test]
	public function testGetAssetsProviderIsNull(): void
	{
		self::assertNull($this->extension->getAssetsProvider());
	}
}
