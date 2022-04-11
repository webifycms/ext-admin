<?php

declare(strict_types=1);

namespace OneCMS\Admin\Infrastructure\Framework\Administration;

use OneCMS\Base\Domain\Service\Administration\AdministrationServiceInterface;
use OneCMS\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;

/**
 * Administration
 *
 * TODO: Add support to the sub-domain if possible. The administration currently supports only sub-path url pattern.
 *
 * @package getonecms/ext-admin
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class Administration implements AdministrationServiceInterface
{
    /**
     * @var string
     */
    private string $path = 'administration';
    /**
     * @var string
     */
    private string $url;
    /**
     * @var bool
     */
    private bool $inAdministration = false;
    /**
     * @var AdministrationMenu
     */
    private AdministrationMenu $menu;

    /**
     * @param WebApplicationServiceInterface $app
     */
    public function __construct(WebApplicationServiceInterface $app, AdministrationMenu $menu)
    {
        $this->path = $app->getAdministrationPath();
        $this->url = $app->getApplication()->getUrlManager()->createAbsoluteUrl('/' . $this->path);
        $requestedUrl = ltrim($app->getApplication()->getRequest()->url, '/');

        if (strpos($requestedUrl, $this->path) !== false) {
            $this->inAdministration = true;
        }

        $this->menu = $menu;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    public function setMenuItems(array $items): void
    {
        $this->menu->addItems($items);
    }

    /**
     * @return AdministrationMenu
     */
    public function getMenu(): AdministrationMenu
    {
        return $this->menu;
    }

    /**
     * @inheritDoc
     */
    public function inAdministration(): bool
    {
        return $this->inAdministration;
    }
}
