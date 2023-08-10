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

namespace Webify\Admin;

use Webify\Admin\Domain\Service\Administration\AdministrationServiceInterface;
use Webify\Admin\Infrastructure\AdminModule;
use Webify\Admin\Infrastructure\Service\Administration\AdministrationService;
use Webify\Base\Infrastructure\Service\Bootstrap\RegisterDependencyBootstrapInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\RegisterRoutesBootstrapInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\WebBootstrapService;

use function Webify\Base\Infrastructure\set_alias;

/**
 * Web application bootstrap class of admin extension.
 */
final class WebBootstrap extends WebBootstrapService implements RegisterDependencyBootstrapInterface, RegisterRoutesBootstrapInterface
{
	public function init(): void
	{
		set_alias('@Admin', \dirname(__DIR__));

		$this->getApplication()->setModule($this->getApplicationService()->getAdministrationPath(), ['class' => AdminModule::class]);
		$this->registerTranslations();
	}

	public function dependencies(): array
	{
		return [
			AdministrationServiceInterface::class => AdministrationService::class,
		];
	}

	public function routes(): array
	{
		$adminPath = $this->getApplicationService()->getAdministrationPath();

		return [
			[
				'route'      => $adminPath,
				'pattern'    => $adminPath,
				'normalizer' => false,
				'suffix'     => false,
			],
			[
				'route'      => $adminPath . '/<controller>/<action>',
				'pattern'    => $adminPath . '/<controller:[\w\-]+>/<action:[\w\-]+>',
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
