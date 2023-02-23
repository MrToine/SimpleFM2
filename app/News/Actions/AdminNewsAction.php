<?php

namespace App\News\Actions;

use App\News\Models\NewsModel;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Contiens les différentes action du module (index, create, views, etc...)
 */
class AdminNewsAction
{
	/**
     *
     * @var mixed
     */
	private $renderer;

	private $table;

	private $router;

    private $flashSession;

	use RouterAwareAction;

	public function __construct(
        RendererInterface $renderer,
        Router $router,
        NewsModel $newsModel,
        FlashService $flashSession)
    {
		$this->table = $newsModel;
        $this->renderer = $renderer;
		$this->router = $router;
        $this->flashSession = $flashSession;
    }

	public function __invoke(Request $request)
    {
        if(substr((string)$request->getUri(), -6) === "create")
        {
            /**
             *
             * Si les 6 derniers caractère de l'url termine par "create" alors on redirige vers la methode adequat
             *
             * */
            return $this->create($request);
        }

        if($request->getMethod() === "DELETE")
        {
            /**
             *
             * Si les 6 derniers caractère de l'url termine par "create" alors on redirige vers la methode adequat
             *
             * */
            return $this->delete($request);
        }

		if($request->getAttribute('id'))
        {
			return $this->edit($request);
        }
		else
        {
            return $this->index($request);
        }
    }

    public function index(Request $request): string
	{
		$params = $request->getQueryParams();
		$items = $this->table->findPaginated(10, $params['p'] ?? 1);

		return $this->renderer->render('@news/admin/index', ['items' => $items]);
	}

	public function edit(Request $request)
    {
        $item = $this->table->find($request->getAttribute('id'));

		if($request->getMethod() === 'POST')
        {
            $params = $this->getParams($request);

            $this->table->update($item->id, $params);

            $this->flashSession->success('L\'article à bien été modifié');

            return $this->redirect('admin.news.index');
        }

		return $this->renderer->render('@news/admin/edit', compact('item'));
    }

    public function create(Request $request)
    {
		if($request->getMethod() === 'POST')
        {
            $params = $this->getParams($request);

            $params = array_merge($params, [
                'updated_date' => date('Y-m-d H:i:s'),
                'created_date' => date('Y-m-d H:i:s'),
            ]);

            $this->table->insert($params);
            $this->flashSession->success('La news à bien été créeer');

            return $this->redirect('admin.news.index');
        }

		return $this->renderer->render('@news/admin/create');
    }

    public function delete(Request $request)
    {
        $item = $this->table->find($request->getAttribute('id'));

        $this->table->delete($item->id);

        $this->flashSession->success('L\'article à bien été supprimé');

        return $this->redirect('admin.news.index');
    }

    private function getParams(Request $request)
    {
        return array_filter($request->getParsedBody(), function ($key) {
                return in_array($key, ['name', 'content', 'slug']);
            }, ARRAY_FILTER_USE_KEY);
    }
}