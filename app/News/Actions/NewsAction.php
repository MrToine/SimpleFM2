<?php

namespace App\News\Actions;

use App\News\Models\CategoryModel;
use App\News\Models\NewsModel;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
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

	private $table;

	private $category;

	private $router;

	use RouterAwareAction;

	public function __construct(RendererInterface $renderer, 
		Router $router, 
		NewsModel $newsModel, 
		CategoryModel $categoryModel)
    {
		$this->table = $newsModel;
		$this->category = $categoryModel;
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
		$news = $this->table->findPaginatedPublic(10, $params['p'] ?? 1);
		$categories = $this->category->findAll();

		return $this->renderer->render('@news/index', compact('news', 'categories'));
	}

	/**
	 * Affiche une news
	 * @param Request $request
	 * @return ResponseInterface|String
	 */
	public function view(Request $request)
	{
		$slug = $request->getAttribute('slug');

		$news = $this->table->find($request->getAttribute('id'));

		if($news->slug !== $slug)
        {
			return $this->redirect('news.view', [
					'slug' => $news->slug,
					'id' => $news->id
				]);
        }

		return $this->renderer->render('@news/view', [
			'news' => $news
		]);
	}
}