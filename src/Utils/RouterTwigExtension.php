<?php

namespace Utils;

use Framework\Router;

class RouterTwigExtension extends \Twig\Extension\AbstractExtension
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('path', [$this, 'pathfor'])
        ];
    }

    public function pathfor(string $path, array $params = []): string
    {
        return $this->router->generateUri($path, $params);
    }
}
