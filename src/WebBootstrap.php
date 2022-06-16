<?php

declare(strict_types=1);

namespace OneCMS\Admin;

use OneCMS\Base\Infrastructure\Service\Bootstrap\WebBootstrapService;
use OneCMS\Admin\Infrastructure\Service\Administration\AdministrationService;
use OneCMS\Admin\Infrastructure\AdminModule;
use OneCMS\Base\Domain\Service\Administration\AdministrationServiceInterface;
use OneCMS\Base\Infrastructure\Service\Bootstrap\RegisterDependencyBootstrapInterface;
use OneCMS\Base\Infrastructure\Service\Bootstrap\RegisterRoutesBootstrapInterface;

/**
 * WebBootstrap
 *
 * @package getonecms/ext-admin
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class WebBootstrap extends WebBootstrapService implements RegisterDependencyBootstrapInterface, RegisterRoutesBootstrapInterface
{
    private string $adminPath = 'administration';

    /**
     * @inheritDoc
     */
    public function dependencies(): array
    {
        return [
            AdministrationServiceInterface::class => AdministrationService::class
        ];
    }

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        set_alias('@Admin', dirname(__DIR__));

        if (isset($this->getApplicationService()->getConfig()['administrationPath'])) {
            $this->adminPath = $this->getApplicationService()->getConfig()['administrationPath'];
        }

        $this->getApplication()->setModule($this->adminPath, ['class' => AdminModule::class]);
        $this->registerAdminMenuItems();
        $this->registerTranslations();
    }

    /**
     * @inheritDoc
     */
    public function routes(): array
    {
        return [
            [
                'route' => $this->adminPath,
                'pattern' => $this->adminPath,
                'normalizer' => false,
                'suffix' => false
            ],
            [
                'route' => $this->adminPath . '/<controller>/<action>',
                'pattern' => $this->adminPath . '/<controller:[\w\-]+>/<action:[\w\-]+>',
                'normalizer' => false,
                'suffix' => false
            ]
        ];
    }

    /**
     * Registering admin menu items.
     */
    private function registerAdminMenuItems(): void
    {
        $this->getApplicationService()->getAdministration()->setMenuItems([
            [
                'label' => 'Dashboard',
                'icon' => 'speedometer',
                'route' => ["/$this->adminPath"],
                'position' => 0,
            ],
            [
                'label' => 'Settings',
                'icon' => 'sliders',
                'route' => '#',
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
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@Admin/resources/translations',
        ];
    }
}
