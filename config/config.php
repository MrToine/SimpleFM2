<?php

use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router\RouterTwigExtension;

/**
 * 
 * Fichier de configuration principal du Framework. Contiens divers informations importante à son bon fonctionnement.
 * 
 * */

return [
    /**
     * DATABASE
     **/

     'database.host' => 'localhost',
     'database.username' => 'root',
     'database.password' => '',
     'database.name' => 'simplefm2',

     /**
      * CONFIG
      * views.path = dossiers vers les vues générique du Framework
      * twig.extensions = liste des extensions personnalisés utilisé avec le moteur de rendu Twig
      * 
      **/

    'views.path' => dirname(__DIR__) . '/app/views',
    'twig.extensions' => [
        \DI\get(RouterTwigExtension::class)
    ],
    \Framework\Router::class => \DI\autowire(),
    RendererInterface::class => \DI\factory(TwigRendererFactory::class)
];
