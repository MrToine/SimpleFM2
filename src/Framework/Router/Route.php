<?php
namespace Framework\Router;

/**
 * Classe Route
 * Représente les routes matchées
 */
class Route
{

    private $name;
    private $callback;
    private $parameters;

    public function __construct(string $name, $callback, array $parameters)
    {
        $this->name = $name;
        $this->callback = $callback;
        $this->parameters = $parameters;
    }

    /**
     *
     * @return [string]
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     *
     * @return [string|callable]
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Récupère les paramètres d'url
     * @return strings[]
     *
     */
    public function getParams(): array
    {
        return $this->parameters;
    }
}
