<?php
namespace App\DefaultModule;

use Framework\Module;
use Framework\Renderer\RendererInterface;

/**
 * Module par defaut servant d'exemple pour la construction d'autres modules au sein du Framework
 * */

class DefaultModule extends Module
{
    public function __construct(RendererInterface $renderer)
    {
        $renderer->addPath('default', __DIR__ . '/Views');
    }

    public function index(Request $request): string
	{
		$params = $request->getQueryParams();
		$news = $this->table->findPaginated(10, $params['p'] ?? 1);

		return $this->renderer->render('@news/index', compact('news'));
	}
}