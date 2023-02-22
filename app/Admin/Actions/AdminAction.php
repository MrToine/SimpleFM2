<?php

namespace App\Admin\Actions;

use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Contiens les différentes action du module (index, create, views, etc...)
 */
class AdminAction
{
	/**
	 *
	 * @var mixed
	 */
	private $renderer;

	private $router;

	use RouterAwareAction;

	public function __construct(RendererInterface $renderer, Router $router)
    {
        $this->renderer = $renderer;
		$this->router = $router;
    }

    public function index(Request $request): string
	{

		return $this->renderer->render('@admin/index');
	}