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
use Webify\Base\Infrastructure\Contract\Administration\AdministrationInterface;
use Webify\Base\Infrastructure\Contract\Provider\ServiceInterface;

/**
 * Proves the service provider gives the container the needed pieces.
 *
 * @internal
 */
#[CoversClass(Service::class)]
#[UsesClass(ServiceDefinitions::class)]
final class ServiceTest extends TestCase
{
	/**
	 * The provider fits the contract.
	 */
	#[Test]
	public function testImplementsServiceProviderInterface(): void
	{
		self::assertInstanceOf(ServiceInterface::class, new Service());
	}

	/**
	 * The provider passes on the definitions it is given, without adding
	 * others of its own.
	 */
	#[Test]
	public function testGetDefinitionsForwardsTheServiceDefinitionsGroup(): void
	{
		$definitions = new Service()->getDefinitions();

		self::assertArrayHasKey(AdministrationInterface::class, $definitions);
		self::assertSame(array_keys(ServiceDefinitions::getDefinitions()), array_keys($definitions));
	}
}
