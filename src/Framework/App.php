<?php

namespace Framework;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{
    /**
     * Router
     * @var Router
     */
    private $router;

    /**
     * [Liste des modules
     * @var array
     */
    private $modules = [];

    /**
     * App Constructeur
     * @param string[] $modules liste des modules à charger
     *
     */

    public function __construct(array $modules = [], array $dependencies = [])
    {
        $this->router = new Router();

        if (array_key_exists('renderer', $dependencies)) {
            $dependencies['renderer']->addGlobal('router', $this->router);
        }

        foreach ($modules as $module) {
            $this->modules[] = new $module($this->router, $dependencies['renderer']);
        }
    }

    public function run(ServerRequestInterface $request): ResponseInterface
    {
        // On récupère le chemin de l'URL
        $uri = $request->getUri()->getPath();
        
        // On vérifie si l'URL se termine par un '/'
        if (!empty($uri) && $uri[-1] === "/") {
            // Si oui, on redirige l'utilisateur sans le '/'
            return (new Response())
                ->withStatus(301)
                ->withHeader('Location', substr($uri, 0, -1));
        }

        $route = $this->router->match($request);
        if (is_null($route)) {
            return new Response(404, [], '<h1>Erreur 404</h1>');
        }

        $params = $route->getParams();

        $request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);

        $response = call_user_func_array($route->getCallback(), [$request]);

        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        } else {
            throw new \Exception('The Response is not a string or an instance of ResponseInterface');
        }
    }
}
