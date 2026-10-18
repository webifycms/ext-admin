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

namespace Webify\Admin\Test\Unit\Infrastructure\Provider;

use PHPUnit\Framework\Attributes\{CoversClass, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Webify\Admin\Infrastructure\Container\ServiceDefinitions;
use Webify\Admin\Infrastructure\Provider\Service;
use Webify\Base\Infrastructure\Contract\{AdministrationInterface, ServiceProviderInterface};

/**
 * ServiceTest tests the extension's service provider.
 *
 * @internal
 */
#[CoversClass(Service::class)]
#[UsesClass(ServiceDefinitions::class)]
final class ServiceTest extends TestCase
{
	/**
	 * Test that the provider implements the service provider contract.
	 */
	#[Test]
	public function testImplementsServiceProviderInterface(): void
	{
		self::assertInstanceOf(ServiceProviderInterface::class, new Service());
	}

	/**
	 * Test that the provider hands the container the service definitions group.
	 *
	 * The provider is the only thing the extension entry point yields to the
	 * container builder, so a provider forwarding nothing would leave every
	 * definition above it unregistered. The keys are compared rather than the
	 * arrays: each call builds fresh definition helpers, which are equal in
	 * content but not identical as objects.
	 */
	#[Test]
	public function testGetDefinitionsForwardsTheServiceDefinitionsGroup(): void
	{
		$definitions = new Service()->getDefinitions();

		self::assertArrayHasKey(AdministrationInterface::class, $definitions);
		self::assertSame(array_keys(ServiceDefinitions::getDefinitions()), array_keys($definitions));
	}
}
