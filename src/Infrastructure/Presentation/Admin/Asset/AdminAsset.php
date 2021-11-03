<?php
declare(strict_types=1);

namespace OneCMS\Admin\Infrastructure\Presentation\Admin\Asset;


use yii\web\AssetBundle;

/**
 * Class AdminAsset
 *
 * @package getonecms/admin
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class AdminAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@Admin/dist';
    /**
     * @inheritdoc
     */
    public $css = [
        'css/app.css',
    ];
    /**
     * @inheritdoc
     */
    public $js = [
        'js/app.js',
    ];
}