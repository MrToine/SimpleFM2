<?php

use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRendererFactory;
use Utils\RouterTwigExtension;
use Utils\TwigPagerFantaExtension;
use Utils\TwigTextExtension;

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
        \DI\get(RouterTwigExtension::class),
        \DI\get(TwigPagerFantaExtension::class),
        \DI\get(TwigTextExtension::class)
    ],
    \Framework\Router::class => \DI\autowire(),

    RendererInterface::class => \DI\factory(TwigRendererFactory::class),

    \PDO::class => function(\Psr\Container\ContainerInterface $c) {
        return new PDO(
                 'mysql:host=' . $c->get('database.host') . ';dbname=' . $c->get('database.name'),
                 $c->get('database.username'),
                 $c->get('database.password'),
                 [
                     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                 ]
            );
    }
];
