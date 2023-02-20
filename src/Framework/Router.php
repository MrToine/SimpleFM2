<?php
namespace Framework;

use Framework\Router\Route;
use Psr\Http\Message\ServerRequestInterface;
use Mezzio\Router\FastRouteRouter;
use Mezzio\Router\Route as MezzioRoute;

/**
 *  Enregistre et match les routes
 */
class Router
{
    /**
     * [$router description]
     * @var FastRouteRouter
     */
    private $router;

    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     * [get description]
     * @param  string   $path     [description]
     * @param  callable $callable [description]
     * @param  string   $name     [description]
     * @return [type]             [description]
     */
    public function get(string $path, callable $callable, string $name)
    {
        $this->router->addRoute(new MezzioRoute($path, $callable, ['GET'], $name));
    }


    /**
     * [match description]
     * @param  RequestInterface $request [description]
     * @return [type]                    [description]
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        $result = $this->router->match($request);

        if ($result->isSuccess()) {
            return new Route(
                $result->getMatchedRouteName(),
                $result->getMatchedMiddleware(),
                $result->getMatchedParams()
            );
        }

        return null;
    }

    public function generateUri(string $name, array $params): ?string
    {
        return $this->router->generateUri($name, $params);
    }
}
