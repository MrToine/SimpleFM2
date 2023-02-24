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
        // On cherche une route qui correspond � la requ�te
        $route = $this->router->match($request);
        if (is_null($route)) {
            return $next($request);
        }
        // On r�cup�re les param�tres de la route
        $params = $route->getParams();

        // On ajoute les param�tres � la requ�te
        $request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);
        
        $request = $request->withAttribute(get_class($route), $route);

        return $next($request);
    }
}
