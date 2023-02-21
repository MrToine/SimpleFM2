<?php

namespace App\News\Actions;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Contiens les différentes action du module (index, create, views, etc...)
 */
class NewsAction
{
	/**
	 * 
	 * @var mixed
	 */
	private $renderer;

	public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

	public function __invoke(Request $request): string
    {
		$slug = $request->getAttribute('slug');
        
		if($slug)
        {
			return $this->view($slug);  
        }
		else
        {
            return $this->index();
        }
    }

    public function index(): string
	{
		return $this->renderer->render('@news/index');
	}

	public function view(string $slug): string
	{
		return $this->renderer->render('@news/view', [
			'slug' => $slug
		]);
	}
}