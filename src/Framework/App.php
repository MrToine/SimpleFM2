<?php

namespace Framework;

use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
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
     * Liste des modules
     * @var array
     */
    private $modules = [];

    /**
     * Contient le container
     * @var mixed
     */
    private $container;

    /**
     * Constructeur d'app
     *
     * @param ContainerInterface $container L'instance du container à utiliser
     * @param array $modules Les modules à charger dans l'application
     */
    public function __construct(ContainerInterface $container, array $modules = [])
    {
        $this->container = $container;

        // On charge les modules dans le container
        foreach ($modules as $module) {
            $this->modules[] = $container->get($module);
        }
    }

    /**
     * Lance l'application
     *
     * @param ServerRequestInterface $request L'objet représentant la requête HTTP
     * @return ResponseInterface L'objet représentant la réponse HTTP
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        // On récupère le chemin de l'URL
        $uri = $request->getUri()->getPath();

        $parsedMethod = $request->getParsedBody();

        if (array_key_exists('_METHOD', $parsedMethod) && in_array($parsedMethod['_METHOD'], ['DELETE', 'PUT'])) {
            $request = $request->withMethod($parsedMethod['_METHOD']);
        }

        // On vérifie si l'URL se termine par un '/'
        if (!empty($uri) && $uri[-1] === "/") {
            // Si oui, on redirige l'utilisateur sans le '/'
            return (new Response())
                ->withStatus(301)
                ->withHeader('Content-Type: text/html; charset=utf-8')
                ->withHeader('Location', substr($uri, 0, -1));
        }

        // On cherche une route qui correspond à la requête
        $router = $this->container->get(Router::class);
        $route = $router->match($request);

        // Si aucune route n'a été trouvée, on renvoie une erreur 404
        if (is_null($route)) {
            return new Response(404, [], '<h1>Erreur 404</h1>');
        }

        // On récupère les paramètres de la route
        $params = $route->getParams();

        // On ajoute les paramètres à la requête
        $request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);

        $callback = $route->getCallback();

        // Si le callback est une chaîne, on essaie de charger la classe correspondante depuis le container
        if (is_string($callback)) {
            $callback = $this->container->get($route->getCallback());
        }

        // On appelle le callback avec la requête
        $response = call_user_func_array($callback, [$request]);

        // On vérifie le type de la réponse et on renvoie la réponse HTTP correspondante
        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        } else {
            throw new \Exception('The Response is not a string or an instance of ResponseInterface');
        }
    }

    /**
     * Retourne le container
     *
     * @return ContainerInterface L'instance du container
     */

    /**
     * Retourne le container
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
