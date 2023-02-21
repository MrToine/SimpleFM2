<?php

use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router\RouterTwigExtension;

/**
 * 
 * Fichier de configuration principal du Framework. Contiens divers informations importante � son bon fonctionnement.
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
      * views.path = dossiers vers les vues g�n�rique du Framework
      * twig.extensions = liste des extensions personnalis�s utilis� avec le moteur de rendu Twig
      * 
      **/

    'views.path' => dirname(__DIR__) . '/app/views',
    'twig.extensions' => [
        \DI\get(RouterTwigExtension::class)
    ],
    \Framework\Router::class => \DI\autowire(),
    RendererInterface::class => \DI\factory(TwigRendererFactory::class)
];
