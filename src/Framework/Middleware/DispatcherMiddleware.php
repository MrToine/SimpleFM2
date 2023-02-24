<?php
namespace Framework\Middleware;

use Framework\Router\Route;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class DispatcherMiddleware
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(\Psr\Http\Message\ServerRequestInterface $request, $next)
    {
        $route = $request->getAttribute(Route::class);
        if (is_null($route)) {
            return $next($request);
        }
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

        return $next($request);
    }
}
