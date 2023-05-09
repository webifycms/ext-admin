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

namespace Webify\Admin\Presentation\Web\Admin\Asset;

use yii\web\AssetBundle;

/**
 * Class AdminAsset.
 */
final class AdminAsset extends AssetBundle
{
	/**
	 * {@inheritdoc}
	 */
	public $sourcePath = '@Admin/dist';

	/**
	 * {@inheritdoc}
	 */
	public $css = [
		'css/app.css',
	];

	/**
	 * {@inheritdoc}
	 */
	public $js = [
		'js/app.js',
	];
}
