<?php

use Framework\Middleware\CsrfMiddleware;
use Framework\Middleware\TrailingSlashMiddleware;
use Framework\Middleware\MethodMiddleware;
use Framework\Middleware\RouterMiddleware;
use Framework\Middleware\DispatcherMiddleware;
use Framework\Middleware\NotFoundMiddleware;

chdir(dirname(__DIR__));

/**
 * Index.php est le point d'entr�e principal du framework.
 */

// Inclure le fichier d'autoloading de Composer.
require 'vendor/autoload.php';

// Les modules ajout�s � l'application.
$modules = [
    App\Admin\AdminModule::class,
    App\Pages\PagesModule::class,
    App\News\NewsModule::class
];

// Instancier le moteur de rendu.
//$renderer = $container->get(\Framework\Renderer\RendererInterface::class);

// Instancier l'application.
$app = (new \Framework\App('config/config.php'))
    ->addModule(\App\Admin\AdminModule::class)
    ->addModule(\App\News\NewsModule::class)
    ->addModule(\App\Pages\PagesModule::class)
    ->pipe(\Middlewares\Whoops::class)
    ->pipe(TrailingSlashMiddleware::class)
    ->pipe(MethodMiddleware::class)
    ->pipe(CsrfMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(DispatcherMiddleware::class)
    ->pipe(NotFoundMiddleware::class) // Dois �tre mis tout � la fin
    ;

// Si l'application est ex�cut�e dans un environnement autre que la ligne de commande.
if (php_sapi_name() != 'cli') {
    // Ex�cuter l'application et r�cup�rer la r�ponse.
    $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());

    // Envoyer la r�ponse au client.
    \Http\Response\send($response);
}
