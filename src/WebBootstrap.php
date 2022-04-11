<?php

declare(strict_types=1);

namespace OneCMS\Admin;

use OneCMS\Base\Infrstructure\Service\Bootstrap\WebBootstrapService;
use OneCMS\Admin\Infrastructure\Framework\Administration\Administration;
use OneCMS\Admin\Infrastructure\Framework\Administration\AdministrationMenu;
use OneCMS\Admin\Infrastructure\Framework\Module;
use OneCMS\Base\Application\Administration\AdministrationInterface;
use OneCMS\Base\Application\Administration\AdministrationMenuInterface;
use OneCMS\Base\Infrastructure\Service\Bootstrap\RegisterDependencyBootstrapInterface;
use OneCMS\Base\Infrastructure\Service\Bootstrap\RegisterRoutesBootstrapInterface;
use OneCMS\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;

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
    /**
     * @inheritDoc
     */
    public function dependencies(): array
    {
        return [
            AdministrationInterface::class => Administration::class,
            AdministrationMenuInterface::class => AdministrationMenu::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function init(WebApplicationServiceInterface $app): void
    {
        parent::init($app);
        set_alias('@Admin', dirname(__DIR__));

        $adminPath = $app->getAdministrationPath();

        $app->setApplicaitonProperty('modules', [
            $adminPath => ['class' => Module::class]
        ]);
        $app->getAdministration()->setMenuItems([
            [
                'label' => 'Dashboard',
                'icon' => 'speedometer',
                'route' => ["/$adminPath"],
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
     * @inheritDoc
     */
    public function routes(): array
    {
        return [
            [
                'route' => $this->administrationPath,
                'pattern' => $this->administrationPath,
                'normalizer' => false,
                'suffix' => false
            ],
            [
                'route' => $this->administrationPath . '/<controller>/<action>',
                'pattern' => $this->administrationPath . '/<controller:[\w\-]+>/<action:[\w\-]+>',
                'normalizer' => false,
                'suffix' => false
            ]
        ];
    }
}
