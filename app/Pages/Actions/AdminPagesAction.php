<?php
namespace App\Pages\Actions;

use App\Pages\Models\CategoryModel;
use App\Pages\Models\PageModel;
use Framework\Actions\CrudAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use Psr\Http\Message\RequestInterface;

/**
 * Contiens les différentes action du module (index, create, views, etc...)
 */
class AdminPagesAction extends CrudAction
{
    protected $viewPath = '@pages/admin/pages';

    protected $routePrefix = 'admin.pages';

    private $categoryTable;

    public function __construct(RendererInterface $renderer,
        Router $router, 
        PageModel $table, 
        FlashService $flashSession,
        CategoryModel $categoryTable)
    {
        parent::__construct($renderer, $router, $table, $flashSession);
        $this->categoryTable = $categoryTable;
    }

    protected function formParams(array $params): array
    {
        $params['categories'] = $this->categoryTable->findList();
        return $params;
    }

    protected function getParams(RequestInterface $request): array
    {
        $params = array_filter($request->getParsedBody(), function ($key) {
                return in_array($key, ['name', 'content', 'slug', 'created_date', 'category_id']);
            }, ARRAY_FILTER_USE_KEY);

        return array_merge($params, [
            'updated_date' => date('Y-m-d H:i:s'),
        ]);
    }

    protected function getValidator(RequestInterface $request)
    {
        return parent::getValidator($request)
            ->required('content', 'name', 'slug', 'created_date', 'category_id')
            ->length('content', 10)
            ->length('name', 2, 150)
            ->length('slug', 2, 150)
            ->exists('category_id', $this->categoryTable->getTable(), $this->categoryTable->getPdo())
            ->slug('slug');
    }
}