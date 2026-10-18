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

namespace Webify\Admin\Test\Architecture;

use Exception;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

/**
 * Architecture tests for the admin extension.
 *
 * The extension only has two layers: Domain (the admin location value object and its
 * exception) and Infrastructure (the controllers, providers and the administration
 * service). Domain is the innermost layer, so it must not reach up into Infrastructure.
 *
 * @internal
 */
final class ArchitectureSpec
{
	/**
	 * Domain classes must not depend on Infrastructure.
	 */
	public function testDomainDoesNotDependOnInfrastructure(): Rule
	{
		return PHPat::rule()
			->classes(Selector::inNamespace('Webify\Admin\Domain'))
			->shouldNot()
			->dependOn()
			->classes(Selector::inNamespace('Webify\Admin\Infrastructure'))
			->because('Domain is the innermost layer and must not depend on Infrastructure')
		;
	}

	/**
	 * All domain exception classes must extend \Exception.
	 */
	public function testDomainExceptionsExtendException(): Rule
	{
		return PHPat::rule()
			->classes(Selector::inNamespace('Webify\Admin\Domain\Exception'))
			->should()
			->extend()
			->classes(Selector::classname(Exception::class))
			->because('All domain exceptions must extend \Exception to ensure consistent error handling')
		;
	}
}
