<?php
declare(strict_types=1);

namespace OneCMS\Admin\Presentation\Web\Admin\Controller;

use yii\web\Controller;

/**
 * Class DashboardController
 *
 * @package getonecms/ext-admin
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class DashboardController extends Controller
{
    
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $this->view->title = 'Dashboard';
        
        return $this->render('index');
    }
    
    /**
     * @return string
     */
    public function actionUpdates(): string
    {
        return $this->render('updates');
    }
}
