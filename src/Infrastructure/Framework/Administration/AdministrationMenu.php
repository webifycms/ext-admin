<?php
declare(strict_types=1);

namespace OneCMS\Admin\Infrastructure\Framework\Administration;

use OneCMS\Base\Application\Administration\AdministrationMenuInterface;
use OneCMS\Base\Infrastructure\Framework\Web\Application\WebApplicationInterface;
use yii\web\View;

/**
 * Class AdministrationMenu
 *
 * @package getonecms/admin
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class AdministrationMenu implements AdministrationMenuInterface
{
    /**
     * @var View
     */
    private View $view;

    /**
     * @param WebApplicationInterface $app
     */
    public function __construct(WebApplicationInterface $app)
    {
        $this->view = $app->getComponent()->getView();
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