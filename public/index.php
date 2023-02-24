<?php

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

// Instancier un builder de conteneur d'injection de dépendances.
$builder = new \DI\ContainerBuilder();

// Ajouter les définitions principales.
$builder->addDefinitions(dirname(__DIR__) . '/config/config.php');

// Ajouter les définitions pour chaque module.
foreach ($modules as $module) {
    if ($module::DEFINITIONS) {
        $builder->addDefinitions($module::DEFINITIONS);
    }
}

// Ajouter les définitions supplémentaires.
$builder->addDefinitions(dirname(__DIR__) . '/config.php');

// Construire le conteneur d'injection de dépendances.
$container = $builder->build();

// Instancier le moteur de rendu.
$renderer = $container->get(\Framework\Renderer\RendererInterface::class);

// Instancier l'application.
$app = new \Framework\App($container, $modules);

// Si l'application est exécutée dans un environnement autre que la ligne de commande.
if (php_sapi_name() != 'cli') {
    // Exécuter l'application et récupérer la réponse.
    $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());

    // Envoyer la réponse au client.
    \Http\Response\send($response);
}
