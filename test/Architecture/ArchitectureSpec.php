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
 * Rules for how the pieces of the extension may depend on each other.
 *
 * The extension has two parts: the inner part (the value object and its
 * exception) must not reach into the outer part (the controllers, providers
 * and services).
 *
 * @internal
 */
final class ArchitectureSpec
{
	/**
	 * The inner part must not depend on the outer part.
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
	 * Every exception in the inner part must be an exception.
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
