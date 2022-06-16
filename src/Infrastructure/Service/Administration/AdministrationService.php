<?php

declare(strict_types=1);

namespace OneCMS\Admin\Infrastructure\Service\Administration;

use OneCMS\Base\Domain\Service\Administration\AdministrationServiceInterface;
use OneCMS\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;

/**
 * AdministrationService
 *
 * TODO: Add support to the sub-domain if possible. The administration currently supports only sub-path url pattern.
 *
 * @package getonecms/ext-admin
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class AdministrationService implements AdministrationServiceInterface
{
    /**
     * @var string
     */
    private readonly string $path;
    /**
     * @var string
     */
    private readonly string $url;
    /**
     * @var bool
     */
    private bool $inAdministration = false;

    /**
     * @param WebApplicationServiceInterface $appService
     */
    public function __construct(
        WebApplicationServiceInterface $appService,
        private readonly AdministrationMenuService $menu
    ) {
        $this->path = $appService->getAdministrationPath();
        $this->url = $appService->getApplication()->getUrlManager()->createAbsoluteUrl('/' . $this->path);
        $requestedUrl = ltrim($appService->getApplication()->getRequest()->url, '/');

        if (str_contains($requestedUrl, $this->path)) {
            $this->inAdministration = true;
        }
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

    public function getMenu(): AdministrationMenuService
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
