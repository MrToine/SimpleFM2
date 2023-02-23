<?php
namespace App\News\Actions;

use App\News\Models\CategoryModel;
use Framework\Actions\CrudAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use Psr\Http\Message\RequestInterface;

/**
 * Contiens les différentes action du module (index, create, views, etc...)
 */
class AdminCategoryAction extends CrudAction
{
    protected $viewPath = '@news/admin/categories';

    protected $routePrefix = 'admin.news.categories';

    public function __construct(RendererInterface $renderer,Router $router, CategoryModel $table, FlashService $flashSession)
    {
        parent::__construct($renderer, $router, $table, $flashSession);
    }
    protected function getParams(RequestInterface $request): array
    {
        return array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['name', 'slug']);
        }, ARRAY_FILTER_USE_KEY);
    }

    protected function getValidator(RequestInterface $request)
    {
        return parent::getValidator($request)
            ->required('name', 'slug')
            ->length('name', 2, 150)
            ->length('slug', 2, 150)
            ->slug('slug');
    }
}