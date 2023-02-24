<?php
namespace Framework\Middleware;

use Framework\Router;

class RouterMiddleware
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    public function __invoke(\Psr\Http\Message\ServerRequestInterface $request, $next)
    {
        // On cherche une route qui correspond à la requête
        $route = $this->router->match($request);
        if (is_null($route)) {
            return $next($request);
        }
        // On récupère les paramètres de la route
        $params = $route->getParams();

        // On ajoute les paramètres à la requête
        $request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);
        
        $request = $request->withAttribute(get_class($route), $route);

        return $next($request);
    }
}
