<?php

use Framework\Middleware\TrailingSlashMiddleware;
use Framework\Middleware\MethodMiddleware;
use Framework\Middleware\RouterMiddleware;
use Framework\Middleware\DispatcherMiddleware;
use Framework\Middleware\NotFoundMiddleware;

/**
 * Index.php est le point d'entrée principal du framework.
 */

// Inclure le fichier d'autoloading de Composer.
require dirname(__DIR__) . '/vendor/autoload.php';

// Les modules ajoutés à l'application.
$modules = [
    App\Admin\AdminModule::class,
    App\Pages\PagesModule::class,
    App\News\NewsModule::class
];

// Instancier le moteur de rendu.
//$renderer = $container->get(\Framework\Renderer\RendererInterface::class);

// Instancier l'application.
$app = (new \Framework\App(dirname(__DIR__) . '/config/config.php'))
    ->addModule(\App\Admin\AdminModule::class)
    ->addModule(\App\News\NewsModule::class)
    ->addModule(\App\Pages\PagesModule::class)
    //->pipe(\Middlewares\Whoops::class)
    ->pipe(TrailingSlashMiddleware::class)
    ->pipe(MethodMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(DispatcherMiddleware::class)
    ->pipe(NotFoundMiddleware::class) // Dois être mis tout à la fin
    ;

// Si l'application est exécutée dans un environnement autre que la ligne de commande.
if (php_sapi_name() != 'cli') {
    // Exécuter l'application et récupérer la réponse.
    $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());

    // Envoyer la réponse au client.
    \Http\Response\send($response);
}
