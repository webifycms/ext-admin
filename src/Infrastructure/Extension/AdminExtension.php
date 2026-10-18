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

namespace Webify\Admin\Infrastructure\Extension;

use Webify\Admin\Infrastructure\Provider\{RoutesRegister, Service};
use Webify\Base\Domain\ValueObject\TemplatePath;
use Webify\Base\Infrastructure\Contract\{AssetsProviderInterface, ExtensionInterface};

/**
 * The admin extension.
 */
final readonly class AdminExtension implements ExtensionInterface
{
	/**
	 * @var string the root path of the extension
	 */
	private string $rootPath;

	/**
	 * The constructor.
	 */
	public function __construct()
	{
		$this->rootPath = dirname(__DIR__, 3);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getId(): string
	{
		return 'webifycms-admin';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSlug(): string
	{
		return 'webifycms-admin';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName(): string
	{
		return 'WebifyCMS Admin Extension';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getProviders(): array
	{
		return [
			new Service(),
			new RoutesRegister(),
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): string
	{
		return '0.2.0-beta';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTemplatePath(): TemplatePath
	{
		return TemplatePath::fromString($this->rootPath . DIRECTORY_SEPARATOR . 'templates');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAssetsProvider(): ?AssetsProviderInterface
	{
		return null;
	}
}
