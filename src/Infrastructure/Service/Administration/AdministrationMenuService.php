<?php

declare(strict_types=1);

namespace Webify\Admin\Infrastructure\Service\Administration;

use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use yii\web\View;

/**
 * Class AdministrationMenuService
 *
 * @package getonecms/ext-admin
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class AdministrationMenuService
{
    /**
     * @var View
     */
    private readonly View $view;

    /**
     * @param WebApplicationServiceInterface $appService
     */
    public function __construct(WebApplicationServiceInterface $appService)
    {
        $this->view = $appService->getApplication()->getView();
    }

    public function addItems(array $items): void
    {
        foreach ($items as $item) {
            $this->view->params['primaryMenuItems'][] = $item;
        }

        $this->sortItems();
    }

    /**
     * Sort menu items based on the given position.
     */
    protected function sortItems(): void
    {
        if (!empty($this->view->params['primaryMenuItems'])) {
            usort($this->view->params['primaryMenuItems'], function ($a, $b) {
                $a['position'] ??= 10;
                $b['position'] ??= 10;

                return $a['position'] <=> $b['position'];
            });
        }
    }
}
