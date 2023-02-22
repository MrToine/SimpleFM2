<?php
namespace App\DefaultModule;

use Framework\Module;
use Framework\Renderer\RendererInterface;
use Psr\Container\ContainerInterface;

/**
 * Module par defaut servant d'exemple pour la construction d'autres modules au sein du Framework
 * */

class DefaultModule extends Module
{
    public function __construct(ContainerInterface $container)
    {
        /*$defaultPrefix = $container->get('default.prefix');*/

        $container->get(RendererInterface::class)->addPath('default', __DIR__ . '/Views');

		/*$router = $container->get(Router::class);

        $router->get($defaultPrefix, NewsAction::class, 'default.index');*/
    }

}