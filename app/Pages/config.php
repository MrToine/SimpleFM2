<?php

use App\News\NewsWidget;

/**
 * Fichier de configuration principal du module. Il permet de d�finir le prefix (route du module) ainsi que le constructeur.
 *
 * */

return [
    'pages.prefix' => '/pages',
    'admin.widgets' => \DI\add([
        
    ])
];