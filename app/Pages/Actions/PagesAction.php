<?php

namespace App\Pages\Actions;

use App\Pages\Models\PageModel;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Contiens les différentes action du module (index, create, views, etc...)
 */
class PagesAction
{
	/**
	 *
	 * @var mixed
	 */
	private $renderer;

	private $table;

	private $router;

	use RouterAwareAction;

	public function __construct(RendererInterface $renderer, Router $router, PageModel $pagesModel)
    {
		$this->table = $pagesModel;
        $this->renderer = $renderer;
		$this->router = $router;
    }

	public function __invoke(Request $request)
    {
		$slug = $request->getAttribute('slug');

		if($slug)
        {
			return $this->view($request);
        }
		else
        {
            return $this->index($request);
        }
    }

    public function index(Request $request): string
	{
		$params = $request->getQueryParams();
		$pages = $this->table->findPaginated(10, $params['p'] ?? 1);

		return $this->renderer->render('@pages/index', compact('pages'));
	}

	/**
	 * Affiche une Pages
	 * @param Request $request
	 * @return ResponseInterface|String
	 */
	public function view(Request $request)
	{
		$slug = $request->getAttribute('slug');

		$pages = $this->table->find($request->getAttribute('id'));

		if($pages->slug !== $slug)
        {
			return $this->redirect('Pages.view', [
					'slug' => $pages->slug,
					'id' => $pages->id
				]);
        }

		return $this->renderer->render('@pages/view', [
			'pages' => $pages
		]);
	}
}