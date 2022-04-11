<?php

declare(strict_types=1);

namespace OneCMS\Admin\Infrastructure;

use RuntimeException;
use Throwable;
use OneCMS\Admin\Presentation\Web\Admin\Asset\AdminAsset;
use OneCMS\Base\Infrastructure\Framework\Theme\ThemeInterface;
use yii\base\Module as BaseModule;
use yii\web\View;

/**
 * Module
 *
 * @package getonecms/ext-admin
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class Module extends BaseModule
{
    public $basePath = '@Admin';
    public $controllerNamespace = 'OneCMS\Admin\Presentation\Web\Admin\Controller';
    public $defaultRoute = 'dashboard';
    public $layout = 'main';

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->setViewPath('@Admin/templates');

        // adding theme support for the templates and register assets
        try {
            $view = $this->get('view');

            $this->addThemeSupport($view);
            $this->registerAssets($view);
        } catch (Throwable $throwable) {
            throw new RuntimeException($throwable->getMessage());
        }
    }

    /**
     * @param View $view
     */
    protected function addThemeSupport(View $view): void
    {
        if ($view->theme instanceof ThemeInterface) {
            $view->theme->pathMap = array_merge($view->theme->pathMap, [
                '@Admin/templates' => '@App/themes/' . $view->theme->getId() . '/templates/' . $this->id
            ]);
        }
    }

    /**
     * @param View $view
     */
    protected function registerAssets(View $view): void
    {
        $view->params['asset'] = AdminAsset::register($view);
    }
}
