<?php

/**
 * Index.php est le point d'entr�e principal du framework.
 */

// Inclure le fichier d'autoloading de Composer.
require dirname(__DIR__) . '/vendor/autoload.php';

// Les modules ajout�s � l'application.
$modules = [
    App\Admin\AdminModule::class,
    App\Pages\PagesModule::class,
    App\News\NewsModule::class
];

// Instancier un builder de conteneur d'injection de d�pendances.
$builder = new \DI\ContainerBuilder();

// Ajouter les d�finitions principales.
$builder->addDefinitions(dirname(__DIR__) . '/config/config.php');

// Ajouter les d�finitions pour chaque module.
foreach ($modules as $module) {
    if ($module::DEFINITIONS) {
        $builder->addDefinitions($module::DEFINITIONS);
    }
}

// Ajouter les d�finitions suppl�mentaires.
$builder->addDefinitions(dirname(__DIR__) . '/config.php');

// Construire le conteneur d'injection de d�pendances.
$container = $builder->build();

// Instancier le moteur de rendu.
$renderer = $container->get(\Framework\Renderer\RendererInterface::class);

// Instancier l'application.
$app = new \Framework\App($container, $modules);

// Si l'application est ex�cut�e dans un environnement autre que la ligne de commande.
if (php_sapi_name() != 'cli') {
    // Ex�cuter l'application et r�cup�rer la r�ponse.
    $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());

    // Envoyer la r�ponse au client.
    \Http\Response\send($response);
}
