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

use Webify\Admin\Infrastructure\AdminModule;
use Webify\Base\Domain\Service\Application\ApplicationServiceInterface as DomainApplicationServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use Webify\Base\Infrastructure\Service\Application\ApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\BaseWebBootstrapService;
use Webify\Base\Infrastructure\Service\Bootstrap\RegisterDependencyBootstrapInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\RegisterRoutesBootstrapInterface;
use function Webify\Base\Infrastructure\get_alias;
use function Webify\Base\Infrastructure\set_alias;

/**
 * Web application bootstrap class of admin extension.
 */
final class WebBootstrapService extends BaseWebBootstrapService
    implements RegisterDependencyBootstrapInterface, RegisterRoutesBootstrapInterface
{
	private string $adminPath;

    /**
     * The class constructor
     */
	public function __construct(
		DependencyServiceInterface $dependencyService,
		DomainApplicationServiceInterface|ApplicationServiceInterface|WebApplicationServiceInterface $appService,
	) {
        set_alias('@Admin', \dirname(__DIR__));

		$this->adminPath = $appService->getAdministrationPath();

		parent::__construct($dependencyService, $appService);
	}

    /**
     * @inheritDoc
     */
	public function dependencies(): array
	{
		return include_once get_alias('@Admin/config/dependencies.php');
	}

    /**
     * @inheritDoc
     */
	public function init(): void
	{
		$this->getApplication()->setModule($this->adminPath, ['class' => AdminModule::class]);
		$this->registerTranslations();
	}

    /**
     * @inheritDoc
     */
	public function routes(): array
	{
        return include_once get_alias('@Admin/config/routes.php');
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
