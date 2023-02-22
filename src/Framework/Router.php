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
     * @param  string|callable $callable [description]
     * @param  string   $name     [description]
     * @return [type]             [description]
     */
    public function get(string $path, $callable, string $name)
    {
        $this->router->addRoute(new MezzioRoute($path, $callable, ['GET'], $name));
    }

    /**
     * Génère les route du CRUD
     * @param string $prefixPath
     * @param mixed $callable
     * @param null|string $prefixName
     */
    public function crud(string $prefixPath, $callable, ?string $prefixName)
    {
        $this->get("$prefixPath", $callable, "$prefixName.index");

        $this->get("$prefixPath/create", $callable, "$prefixName.create");
        $this->post("$prefixPath/create", $callable);

        $this->get("$prefixPath/{id:\d+}", $callable, "$prefixName.edit");
        $this->post("$prefixPath/{id:\d+}", $callable);

        $this->delete("$prefixPath/{id:\d+}", $callable, "$prefixName.delete");
    }

    /**
     * Summary of post
     * @param string $path
     * @param mixed $callable
     * @param null|string $name
     */
    public function post(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new MezzioRoute($path, $callable, ['POST'], $name));
    }

    public function delete(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new MezzioRoute($path, $callable, ['DELETE'], $name));
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

    public function generateUri(string $name, array $params = [], array $queryParams = []): ?string
    {
        $uri = $this->router->generateUri($name, $params);

        if (!empty($queryParams)) {
            return $uri . '?' . http_build_query($queryParams);
        }
        return $uri;
    }
}
