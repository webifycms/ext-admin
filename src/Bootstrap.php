<?php
declare(strict_types=1);

namespace OneCMS\Admin;


use OneCMS\Admin\Infrastructure\Framework\Administration\Administration;
use OneCMS\Admin\Infrastructure\Framework\Administration\AdministrationMenu;
use OneCMS\Admin\Infrastructure\Framework\Module;
use OneCMS\Base\Application\Administration\AdministrationInterface;
use OneCMS\Base\Application\Administration\AdministrationMenuInterface;
use OneCMS\Base\Infrastructure\Framework\Bootstrap\AbstractBootstrap;
use OneCMS\Base\Infrastructure\Framework\Bootstrap\RegisterDependencyBootstrapInterface;
use OneCMS\Base\Infrastructure\Framework\Bootstrap\RegisterRoutesBootstrapInterface;
use OneCMS\Base\Infrastructure\Framework\Web\Application\WebApplicationInterface;

/**
 * Class Bootstrap
 *
 * @package getonecms/admin
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class Bootstrap extends AbstractBootstrap implements RegisterDependencyBootstrapInterface, RegisterRoutesBootstrapInterface
{
    /**
     * @var string
     */
    private string $administrationPath = 'administration';

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
     * @param WebApplicationInterface $app
     */
    public function init(WebApplicationInterface $app): void
    {
        parent::init($app);
        set_alias('@Admin', dirname(__DIR__));

        $this->administrationPath = $app->getConfig()->get('administrationPath');

        $app->set('modules', [
            $this->administrationPath => ['class' => Module::class]
        ]);
        $app->getAdministration()->getMenu()->addItems([
            [
                'label' => 'Dashboard',
                'icon' => 'speedometer',
                'route' => ["/$this->administrationPath"],
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