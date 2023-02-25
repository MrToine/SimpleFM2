<?php

namespace Framework;

use DI\ContainerBuilder;
use GuzzleHttp\Psr7\Response;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class App implements RequestHandlerInterface
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

    private $definition;

    /**
     * liste des middlewares
     * @var string[]
     */
    private $middlewares;

    private $index = 0;

    /**
     * Instance de container interface
     * @var ContainerInterface
     */
    private $container;

    public function __construct(string $definition)
    {
        $this->definition = $definition;
    }

    /**
     * Rajoute un module à l'application
     * @param string $module
     * @return App
     */
    public function addModule(string $module): self
    {
        $this->modules[] = $module;

        return $this;
    }

    /**
     * Rajoute un middleware (comportement)
     * @param string $middleware
     * @return App
     */
    public function pipe(string $middleware): self
    {
        $this->middlewares[] = $middleware;

        return $this;
    }

    
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = $this->getMiddleware();
        if ($middleware === null) {
            throw new \RuntimeException("Aucun middleware n'a intercepté cette requête");
        } elseif (is_callable($middleware)) {
            return call_user_func($middleware, $request, [$this, 'handle']);
        } elseif ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $this);
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
        foreach ($this->modules as $module) {
            $this->getContainer()->get($module);
        }

        return $this->handle($request);
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
        if ($this->container === null) {
            // Instancier un builder de conteneur d'injection de dépendances.
            $builder = new ContainerBuilder();

            // Ajouter les définitions principales.
            $builder->addDefinitions($this->definition);

            // Ajouter les définitions pour chaque module.
            foreach ($this->modules as $module) {
                if ($module::DEFINITIONS) {
                    $builder->addDefinitions($module::DEFINITIONS);
                }
            }

            // Construire le conteneur d'injection de dépendances.
            $this->container = $builder->build();
        }

        return $this->container;
    }

    private function getMiddleware()
    {
        if (array_key_exists($this->index, $this->middlewares)) {
            $middleware = $this->container->get($this->middlewares[$this->index]);
            $this->index++;
            return $middleware;
        }
        return null;
    }
}
