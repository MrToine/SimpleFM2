<?php
namespace App\News;

use Framework\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class NewsModule
{
	public function __construct(Router $router)
	{
		$router->get('/news', [$this, 'index'], 'news.index');
		$router->get('/news/{slug}', [$this, 'view'], 'news.view');
	}

	public function index(Request $request): string
	{
		return '<h1>Bienvenue sur les actus</h1>';
	}
	
	public function view(Request $request): string
	{
		return '<h1>Bienvenue sur la news ' . $request->getAttribute('slug') . '</h1>';
	}
}