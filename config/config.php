<?php

use Framework\Middleware\CsrfMiddleware;
use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRendererFactory;
use Framework\Session\PHPSession;
use Framework\Session\SessionInterface;
use Psr\Container\ContainerInterface;
use Utils\{
    CsrfTwigExtension,
    TwigFlashExtension,
    TwigPagerFantaExtension,
    TwigTextExtension,
    TwigFormExtension,
    RouterTwigExtension
};

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
        \DI\get(RouterTwigExtension::class),
        \DI\get(TwigPagerFantaExtension::class),
        \DI\get(TwigTextExtension::class),
        \DI\get(TwigFlashExtension::class),
        \DI\get(TwigFormExtension::class),
        \DI\get(CsrfTwigExtension::class),
    ],

    /**
     *
     * On charge le rendu de Twig
     *
     * */
    RendererInterface::class => \DI\factory(TwigRendererFactory::class),


    /**
     *
     * On fait appel � la session interface et la PHPSession pour s'en servir quand on � besoin
     *
     * */

    SessionInterface::class => \DI\create(PHPSession::class),
    CsrfMiddleware::class => \DI\autowire()->constructorParameter('session', \DI\get(SessionInterface::class)),


    \Framework\Router::class => \DI\autowire(),

    \PDO::class => function(ContainerInterface $c) {
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
