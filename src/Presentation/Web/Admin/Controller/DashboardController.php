<?php

declare(strict_types=1);

namespace OneCMS\Admin\Presentation\Web\Admin\Controller;

use yii\web\Controller;

/**
 * DashboardController
 *
 * @package getonecms/ext-admin
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class DashboardController extends Controller
{

    public function actionIndex(): string
    {
        $this->view->title = 'Dashboard';

        return $this->render('index');
    }

    public function actionUpdates(): string
    {
        return $this->render('updates');
    }
}
