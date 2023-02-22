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
            new \Twig\TwigFunction('path', [$this, 'pathfor']),
            new \Twig\TwigFunction('asset', [$this, 'getAsset'])
        ];
    }

    public function pathfor(string $path, array $params = []): string
    {
        return $this->router->generateUri($path, $params);
    }

    public function getAsset(string $path, array $params = []): string
    {
        $rootPath = $_SERVER['DOCUMENT_ROOT'];
        $assetPath = $rootPath . '/asset/' . $path;
        return str_replace($rootPath, '', $assetPath);
    }
}
