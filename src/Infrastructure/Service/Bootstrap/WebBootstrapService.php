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

namespace Webify\Admin\Infrastructure\Service\Bootstrap;

use Webify\Admin\Infrastructure\Service\Menu\PrimaryMenuItemService;
use Webify\Admin\Infrastructure\Service\ViewInjector\PrimaryMenuViewInjectorService;
use Webify\Base\Domain\Exception\TranslatableRuntimeException;
use Webify\Base\Domain\Service\Application\ApplicationServiceInterface;
use Webify\Base\Domain\Service\Config\ConfigServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\BaseWebBootstrapService;
use Webify\Base\Infrastructure\Service\Bootstrap\RegisterAdminRoutesBootstrapInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\RegisterControllerNamespaceBootstrapInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\RegisterDependenciesBootstrapInterface;
use Webify\Base\Infrastructure\Service\ViewInjector\ViewInjectorRegistryService;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\web\AssetManager;

use function Webify\Base\Infrastructure\get_alias;
use function Webify\Base\Infrastructure\set_alias;

/**
 * Web application bootstrap class of admin extension.
 */
final class WebBootstrapService extends BaseWebBootstrapService implements RegisterDependenciesBootstrapInterface, RegisterAdminRoutesBootstrapInterface, RegisterControllerNamespaceBootstrapInterface
{
	/**
	 * The extension templates path.
	 */
	public const TEMPLATES_PATH = '@Admin/templates';

	/**
	 * The class constructor.
	 */
	public function __construct(
		DependencyServiceInterface $dependencyService,
		ConfigServiceInterface $configService,
	) {
		set_alias('@Admin', '@Extensions/ext-admin');

		parent::__construct($dependencyService, $configService);
	}

	public function bootstrap(ApplicationServiceInterface|WebApplicationServiceInterface $appService): void
	{
		if ($appService instanceof WebApplicationServiceInterface) {
			$appService->getApplication()->i18n->translations['admin*'] = [
				'class'          => 'yii\i18n\PhpMessageSource',
				'sourceLanguage' => 'en-US',
				'basePath'       => '@Admin/resources/translations',
			];

			/**
			 * @var ViewInjectorRegistryService $viewInjectorRegistry
			 */
			$viewInjectorRegistry = $appService->getService(ViewInjectorRegistryService::class);

			/**
			 * @var PrimaryMenuViewInjectorService $primaryMenuInjector
			 */
			$primaryMenuInjector = $appService->getService(PrimaryMenuViewInjectorService::class);
			$assetUrl            = $this->publishAssets($appService->getApplication()->assetManager);

			$primaryMenuInjector->register(new PrimaryMenuItemService($assetUrl));
			$viewInjectorRegistry->register($primaryMenuInjector);
		}
	}

	public function dependencies(): array
	{
		return require get_alias('@Admin/config/dependencies.php');
	}

	public function adminRoutes(): array
	{
		return require get_alias('@Admin/config/routes.php');
	}

	public function controllerNamespaces(): array
	{
		return [
			'Webify\Admin\Infrastructure\Presentation\Web\Controller\Admin',
		];
	}

	private function publishAssets(AssetManager $assetManager): string
	{
		try {
			$published = $assetManager->publish('@Admin/resources/assets');

			return $published[1];
		} catch (InvalidArgumentException|InvalidConfigException $exception) {
			throw new TranslatableRuntimeException(
				'Unable to publish admin extension assets.',
				['directory' => '@Admin/resources/assets'],
				$exception->getCode(),
				$exception
			);
		}
	}
}
