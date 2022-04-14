<?php

declare(strict_types=1);

namespace OneCMS\Admin;

use OneCMS\Base\Infrastructure\Service\Bootstrap\WebBootstrapService;
use OneCMS\Admin\Infrastructure\Framework\Administration\Administration;
use OneCMS\Admin\Infrastructure\Framework\Module;
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
    private ?string $adminPath = null;

    /**
     * @inheritDoc
     */
    public function dependencies(): array
    {
        return [
            AdministrationServiceInterface::class => Administration::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        set_alias('@Admin', dirname(__DIR__));

        $this->adminPath = $this->getApplicationService()->getAdministrationPath();

        $this->getApplicationService()->setApplicaitonProperty('modules', [
            $this->adminPath => ['class' => Module::class]
        ]);

        $administration = $this->getDependencyService()->getContainer()->get(AdministrationServiceInterface::class);

        $administration->setMenuItems([
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
}
