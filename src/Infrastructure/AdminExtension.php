<?php

/**
 * The file is part of the "webifycms/ext-admin", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/admin
 *
 * @copyright Copyright (c) 2023 WebifyCMS
 * @license https://webifycms.com/extension/admin/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Admin\Infrastructure;

use Webify\Admin\Domain\AdminExtensionInterface;
use Webify\Admin\Infrastructure\Service\Administration\PrimaryMenuItemsAdministrationService;
use Webify\Admin\Infrastructure\Service\Register\AdminRoutesRegisterService;
use Webify\Admin\Infrastructure\Service\Register\ControllerNamespaceRegisterService;
use Webify\Admin\Infrastructure\Service\Register\DependenciesRegisterService;
use Webify\Admin\Infrastructure\Service\Register\TranslationsRegisterService;
use Webify\Base\Domain\Exception\TranslatableRuntimeException;
use Webify\Base\Domain\Service\Application\ApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Administration\PrimaryMenuAdministrationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\web\AssetManager;

use function strtolower;
use function Webify\Base\Infrastructure\dependency;
use function Webify\Base\Infrastructure\get_alias;
use function Webify\Base\Infrastructure\set_alias;

/**
 * The Admin extension.
 */
final class AdminExtension implements AdminExtensionInterface
{
	/**
	 * @var null|string the extension templates path
	 */
	private ?string $assetsPath = null;

	/**
	 * @var null|string the extension assets published url
	 */
	private ?string $assetsUrl = null;

	/**
	 * The constructor.
	 */
	public function __construct()
	{
		set_alias('@Admin', '@Extensions/ext-admin');
	}

	public function initialize(ApplicationServiceInterface $appService): void
	{
		if ($appService instanceof WebApplicationServiceInterface) {
			$this->publishAssets($appService->getApplication()->assetManager);

			/**
			 * @var PrimaryMenuAdministrationServiceInterface $primaryMenuService
			 */
			$primaryMenuService = $appService->getService(PrimaryMenuAdministrationServiceInterface::class);

			$primaryMenuService->register(new PrimaryMenuItemsAdministrationService((string) $this->assetsUrl));
		}
	}

	public function getId(): string
	{
		return strtolower(self::NAME);
	}

	public function getInterface(): string
	{
		return AdminExtensionInterface::class;
	}

	public function getVersion(): string
	{
		return self::VERSION;
	}

	public function getTemplatesPath(): ?string
	{
		return get_alias(self::TEMPLATES_PATH);
	}

	public function getAssetsPath(): ?string
	{
		return $this->assetsPath;
	}

	public function getAssetsPublishedUrl(): ?string
	{
		return $this->assetsUrl;
	}

	/**
	 * @return string[]
	 */
	public function getRegisterServices(): array
	{
		return [
			DependenciesRegisterService::class,
			ControllerNamespaceRegisterService::class,
			AdminRoutesRegisterService::class,
			TranslationsRegisterService::class,
		];
	}

	public static function getInstance(): AdminExtensionInterface
	{
		// @phpstan-ignore-next-line
		return dependency()
			->getContainer()
			->get(WebApplicationServiceInterface::class)
			->getExtension(AdminExtensionInterface::class)
		;
	}

	/**
	 * Publishes assets to a public directory using the given asset manager.
	 *
	 * @param AssetManager $assetManager the asset manager responsible for publishing assets
	 *
	 * @throws TranslatableRuntimeException if the assets cannot be published
	 */
	private function publishAssets(AssetManager $assetManager): void
	{
		try {
			$published        = $assetManager->publish(self::ASSETS_PATH);
			$this->assetsPath = $published[0];
			$this->assetsUrl  = $published[1];
		} catch (InvalidArgumentException|InvalidConfigException $exception) {
			throw new TranslatableRuntimeException(
				'admin_assets_publish_failed.',
				['directory' => self::ASSETS_PATH],
				$exception->getCode(),
				$exception
			);
		}
	}
}
