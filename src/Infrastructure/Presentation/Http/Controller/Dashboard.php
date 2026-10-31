<?php

/**
 * The file is part of the "webifycms/ext-admin", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/admin
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Admin\Infrastructure\Presentation\Http\Controller;

use Psr\Http\Message\{ResponseFactoryInterface, ResponseInterface, ServerRequestInterface, StreamFactoryInterface};
use Webify\Base\Infrastructure\Contract\Template\TemplateViewInterface;
use Webify\Base\Infrastructure\Presentation\Http\Controller\Controller;

/**
 * Shows the administration home page.
 */
final readonly class Dashboard extends Controller
{
	public function __construct(
		ResponseFactoryInterface $responseFactory,
		StreamFactoryInterface $streamFactory,
		TemplateViewInterface $view,
	) {
		parent::__construct($responseFactory, $streamFactory, $view);
	}

	/**
	 * Shows the dashboard inside the theme's admin frame.
	 */
	public function __invoke(ServerRequestInterface $request): ResponseInterface
	{
		return $this->render('dashboard/index', ['currentPage' => 'dashboard'], 'admin/layouts/main');
	}
}
