<?php
use App\Admin\Actions\DashboardAction;
use App\Admin\AdminModule;
use Framework\Renderer\RendererInterface;
use Framework\Router;

return [
    'admin.prefix' => '/admin',

    'admin.widgets' => [],

    AdminModule::class => \DI\create()->constructor(
        \DI\get(RendererInterface::class),
        \DI\get(Router::class),
        \DI\get('admin.prefix') // On récupère la valeur définie précédemment
    ),
    DashboardAction::class => \DI\create()->constructor(
        \DI\get(RendererInterface::class),
        \DI\get('admin.widgets')
    ),
];
