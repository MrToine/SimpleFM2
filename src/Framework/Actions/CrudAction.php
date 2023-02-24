<?php
namespace Framework\Actions;

use Framework\Database\Model;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use Framework\Validator;
use Psr\Http\Message\RequestInterface;

class CrudAction
{
    /**
     *
     * @var RendererInterface
     */
    private $renderer;

    /**
     *
     * @var Model
     */
    private $table;

    /**
     *
     * @var Router
     */
    private $router;

    /**
     *
     * @var FlashService
     */
    private $flashSession;

    /**
     * Chemin des vues
     * @var string
     */
    protected $viewPath;

    protected $routePrefix;

    protected $messagesFlash = [
            'create' => "L'élement à bien été créer",
            'edit' => "L'élement à bien été modifier",
            'delete' => "L'élement à bien été supprimé"
        ];

    use RouterAwareAction;

    public function __construct(
        RendererInterface $renderer,
        Router $router,
        Model $table,
        FlashService $flashSession
    ) {
        $this->table = $table;
        $this->renderer = $renderer;
        $this->router = $router;
        $this->flashSession = $flashSession;
    }

    public function __invoke(RequestInterface $request)
    {
        $this->renderer->addGlobal('viewPath', $this->viewPath);
        $this->renderer->addGlobal('routePrefix', $this->routePrefix);
        if (substr((string)$request->getUri(), -6) === "create") {
            /**
             *
             * Si les 6 derniers caractère de l'url termine par "create" alors on redirige vers la methode adequat
             *
             * */
            return $this->create($request);
        }

        if ($request->getMethod() === "DELETE") {
            /**
             *
             * Si les 6 derniers caractère de l'url termine par "create" alors on redirige vers la methode adequat
             *
             * */
            return $this->delete($request);
        }

        if ($request->getAttribute('id')) {
            return $this->edit($request);
        } else {
            return $this->index($request);
        }
    }

    /**
     * Génère une page index générique pour le CRUD
     * @param RequestInterface $request 
     * @return bool|string
     */
    public function index(RequestInterface $request): string
    {
        $params = $request->getQueryParams();
        $items = $this->table->findPaginated(10, $params['p'] ?? 1);

        return $this->renderer->render($this->viewPath . '/index', ['items' => $items]);
    }

    /**
     * Edition d'un élement
     * @param RequestInterface $request
     * @return \GuzzleHttp\Psr7\Response|\Psr\Http\Message\MessageInterface|bool|string
     */
    public function edit(RequestInterface $request)
    {
        $errors = null;

        $item = $this->table->find($request->getAttribute('id'));

        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);

            $validator = $this->getValidator($request);

            if ($validator->isValid()) {
                $this->table->update($item->id, $params);
                $this->flashSession->success($this->messagesFlash['create']);
                return $this->redirect($this->routePrefix . '.index');
            }
            $errors = $validator->getErrors();
            $params['id'] = $item->id;
            $item = $params;
        }

        return $this->renderer->render(
            $this->viewPath . '/edit',
            $this->formParams(compact('item', 'errors'))
        );
    }

    /**
     * Création d'un élement
     * @param RequestInterface $request
     * @return \GuzzleHttp\Psr7\Response|\Psr\Http\Message\MessageInterface|bool|string
     */
    public function create(RequestInterface $request)
    {

        $errors = null;
        $items = null;

        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);

            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->table->insert($params);
                $this->flashSession->success($this->messagesFlash['edit']);
                return $this->redirect($this->routePrefix . '.index');
            }
            $items = $params;
            $errors = $validator->getErrors();
        }

        return $this->renderer->render(
            $this->viewPath . '/create',
            $this->formParams(compact('item', 'errors'))
        );
    }

    /**
     * Suppressin d'un élement
     * @param RequestInterface $request
     * @return \GuzzleHttp\Psr7\Response|\Psr\Http\Message\MessageInterface
     */
    public function delete(RequestInterface $request)
    {
        $item = $this->table->find($request->getAttribute('id'));

        $this->table->delete($item->id);

        $this->flashSession->success($this->messagesFlash['delete']);

        return $this->redirect($this->routePrefix . '.index');
    }

    /**
     * Filtre les paramètres récu pour la requête
     * @param RequestInterface $request
     * @return array|null|object
     */
    protected function getParams(RequestInterface $request): array
    {
        return array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, []);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * génère le validateur de formulaire
     * @param RequestInterface $request
     * @return Validator
     */
    protected function getValidator(RequestInterface $request)
    {
        return (new Validator($request->getParsedBody()));
    }

    /**
     * Génère une nouvelle entité pour l'action de création
     * @return array
     */
    protected function getNewEntity()
    {
        return [];
    }

    /**
     * Permet de traiter les paramètres envoyés à la vue
     * @param mixed $params
     * @return mixed
     */
    protected function formParams(array $params): array
    {
        return $params;
    }
}
