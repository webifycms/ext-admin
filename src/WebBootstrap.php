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

use Webify\Admin\Infrastructure\AdminModule;
use Webify\Admin\Infrastructure\Service\Administration\AdministrationService;
use Webify\Base\Domain\Service\Administration\AdministrationServiceInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\RegisterDependencyBootstrapInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\RegisterRoutesBootstrapInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\WebBootstrapService;

use function Webify\Base\Infrastructure\set_alias;

/**
 * Web application bootstrap class of admin extension.
 */
final class WebBootstrap extends WebBootstrapService implements RegisterDependencyBootstrapInterface, RegisterRoutesBootstrapInterface
{
	private string $adminPath = 'administration';

	/**
	 * {@inheritDoc}
	 */
	public function dependencies(): array
	{
		return [
			AdministrationServiceInterface::class => AdministrationService::class,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function init(): void
	{
		set_alias('@Admin', \dirname(__DIR__));

		if (isset($this->getApplicationService()->getConfig()['administrationPath'])) {
			$this->adminPath = $this->getApplicationService()->getConfig()['administrationPath'];
		}

		$this->getApplication()->setModule($this->adminPath, ['class' => AdminModule::class]);
		$this->registerAdminMenuItems();
		$this->registerTranslations();
	}

	/**
	 * {@inheritDoc}
	 */
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
	 * Registering admin menu items.
	 */
	private function registerAdminMenuItems(): void
	{
		$this->getApplicationService()->getAdministration()->setMenuItems([
			[
				'label'    => 'Dashboard',
				'icon'     => 'speedometer',
				'route'    => ["/{$this->adminPath}"],
				'position' => 0,
			],
			[
				'label'    => 'Settings',
				'icon'     => 'sliders',
				'route'    => '#',
				'position' => 10,
			],
		]);
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
