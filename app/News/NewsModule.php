<?php
namespace App\News;

use App\News\Actions\NewsAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;

class NewsModule extends Module
{
	
	const DEFINITIONS = __DIR__ . '/config.php';

	public function __construct(string $prefix, Router $router, RendererInterface $renderer)
	{
        $renderer->addPath('news', __DIR__ . '/views');

        $router->get($prefix, NewsAction::class, 'news.index');
        $router->get($prefix . '/news/{slug}', NewsAction::class, 'news.view');
	}
}