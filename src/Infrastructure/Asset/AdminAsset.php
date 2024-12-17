<?php
/**
 * The file is part of the "webifycms/ext-admin", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/admin
 *
 * @copyright Copyright (c) 2023 WebifyCMS
 * @license https://webifycms.com/extension/admin/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Admin\Infrastructure\Asset;

use yii\web\AssetBundle;

/**
 * Class AdminAsset.
 */
final class AdminAsset extends AssetBundle
{
	public $sourcePath = '@Admin/dist';

	public $css = [
		'css/app.css',
	];

	public $js = [
		'js/app.js',
	];
}
