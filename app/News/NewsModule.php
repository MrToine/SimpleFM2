<?php
namespace App\News;

use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class NewsModule
{
	private $renderer;
	public function __construct(Router $router, RendererInterface $renderer)
	{
		$this->renderer = $renderer;
		$this->renderer->addPath('news', __DIR__ . '/views');

		$router->get('/news', [$this, 'index'], 'news.index');
		$router->get('/news/{slug}', [$this, 'view'], 'news.view');
	}

	public function index(Request $request): string
	{
		return $this->renderer->render('@news/index');
	}

	public function view(Request $request): string
	{
		return $this->renderer->render('@news/view', [
			'slug' => $request->getAttribute('slug')	
		]);
	}
}