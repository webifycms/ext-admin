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

use Webify\Admin\Domain\Service\Administration\AdministrationServiceInterface;
use Webify\Admin\Infrastructure\Service\Administration\AdministrationService;
use Webify\Base\Domain\Service\Application\ApplicationServiceInterface as DomainApplicationServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use Webify\Base\Infrastructure\Service\Application\ApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\RegisterDependencyBootstrapInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\RegisterRoutesBootstrapInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\WebBootstrapService;

use function Webify\Base\Infrastructure\set_alias;

/**
 * Web application bootstrap class of admin extension.
 */
final class WebBootstrap extends WebBootstrapService implements RegisterDependencyBootstrapInterface, RegisterRoutesBootstrapInterface
{
	private string $adminPath;

	public function __construct(
		DependencyServiceInterface $dependencyService,
		DomainApplicationServiceInterface|ApplicationServiceInterface|WebApplicationServiceInterface $appService,
	) {
		$this->adminPath = $appService->getAdministrationPath();

		parent::__construct($dependencyService, $appService);
	}

	public function dependencies(): array
	{
		return [
			AdministrationServiceInterface::class => AdministrationService::class,
		];
	}

	public function init(): void
	{
		set_alias('@Admin', \dirname(__DIR__));

		$this->getApplication()->setModule($this->adminPath, ['class' => AdminModule::class]);
		$this->registerTranslations();
	}

	public function routes(): array
	{
		return [
			[
				'route'      => $this->adminPath,
				'pattern'    => $this->adminPath,
				'normalizer' => false,
				'suffix'     => false,
			],
			[
				'route'      => $this->adminPath . '/<controller>/<action>',
				'pattern'    => $this->adminPath . '/<controller:[\w\-]+>/<action:[\w\-]+>',
				'normalizer' => false,
				'suffix'     => false,
			],
		];
	}

	/**
	 * Register translations for admin extension.
	 */
	private function registerTranslations(): void
	{
		$this->getApplication()->i18n->translations['admin*'] = [
			'class'          => 'yii\i18n\PhpMessageSource',
			'sourceLanguage' => 'en-US',
			'basePath'       => '@Admin/resources/translations',
		];
	}
}
