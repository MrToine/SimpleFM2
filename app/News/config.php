<?php

use App\News\NewsWidget;

/**
 * Fichier de configuration principal du module. Il permet de définir le prefix (route du module) ainsi que le constructeur.
 *
 * */

return [
    'news.prefix' => '/news',
    'admin.widgets' => \DI\add([
        DI\get(NewsWidget::class),
    ])
];