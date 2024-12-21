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

use Webify\Admin\Domain\Service\Administration\AdministrationServiceInterface;
use Webify\Admin\Infrastructure\Service\Administration\AdministrationService;
use Webify\Base\Domain\Service\Application\ApplicationServiceInterface;
use yii\di\Container;

use function Webify\Base\Infrastructure\dependency;

/**
 * @var Container $container
 */
$container = dependency()->getContainer();

return [
	AdministrationServiceInterface::class => fn () => new AdministrationService(
		$container->get(ApplicationServiceInterface::class)
	),
];
