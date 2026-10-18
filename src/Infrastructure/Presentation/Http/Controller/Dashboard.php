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
use Webify\Base\Infrastructure\Contract\ViewInterface;
use Webify\Base\Infrastructure\Presentation\Http\Controller\Controller;

/**
 * Dashboard controller.
 */
final readonly class Dashboard extends Controller
{
	/**
	 * The constructor.
	 */
	public function __construct(
		ResponseFactoryInterface $responseFactory,
		StreamFactoryInterface $streamFactory,
		ViewInterface $view,
	) {
		parent::__construct($responseFactory, $streamFactory, $view);
	}

	/**
	 * Handles the HTTP request/response lifecycle of the dashboard page.
	 */
	public function __invoke(ServerRequestInterface $request): ResponseInterface
	{
		return $this->render('dashboard/index', ['currentPage' => 'dashboard'], 'admin/layouts/main');
	}
}
