<?php
declare(strict_types=1);

namespace OneCMS\Admin\Infrastructure\Framework\Administration;

use OneCMS\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use yii\web\View;

/**
 * Class AdministrationMenu
 *
 * @package getonecms/ext-admin
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class AdministrationMenu
{
    /**
     * @var View
     */
    private View $view;

    /**
     * @param WebApplicationServiceInterface $app
     */
    public function __construct(WebApplicationServiceInterface $app)
    {
        $this->view = $app->getApplication()->getView();
    }

    /**
     * @param array $items
     */
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
                $a['position'] = $a['position'] ?? 10;
                $b['position'] = $b['position'] ?? 10;

                return $a['position'] <=> $b['position'];
            });
        }
    }
}