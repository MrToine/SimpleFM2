<?php
use function DI\autowire;

use \App\News\NewsModule;

/**
 * Fichier de configuration principal du module. Il permet de définir le prefix (route du module) ainsi que le constructeur.
 * 
 * */

return [
    'news.prefix' => '/news',
    NewsModule::class => \DI\autowire()->constructorParameter('prefix', \DI\get('news.prefix'))
];