<?php
declare(strict_types=1);

namespace OneCMS\Admin\Infrastructure\Framework\Administration;


use OneCMS\Base\Application\Administration\AdministrationInterface;
use OneCMS\Base\Application\Administration\AdministrationMenuInterface;
use OneCMS\Base\Infrastructure\Framework\Web\Application\WebApplicationInterface;

/**
 * Class Administration
 *
 * TODO: Add support to the sub-domain if possible. The administration currently supports only sub-path url pattern.
 *
 * @package getonecms/admin
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class Administration implements AdministrationInterface
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
     * @var AdministrationMenuInterface
     */
    private AdministrationMenuInterface $menu;

    /**
     * @param WebApplicationInterface $app
     * @param AdministrationMenuInterface $menu
     */
    public function __construct(WebApplicationInterface $app, AdministrationMenuInterface $menu)
    {
        $this->menu = $menu;
        $this->path = $app->getConfig()->get('administrationPath') ?? $this->path;
        $this->url = $app->getComponent()->getUrlManager()->createAbsoluteUrl('/' . $this->path);
        $requestedUrl = ltrim($_SERVER['REQUEST_URI'], '/');

        if (strpos($requestedUrl, $this->path) !== false) {
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

    /**
     * @return AdministrationMenuInterface
     */
    public function getMenu(): AdministrationMenuInterface
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