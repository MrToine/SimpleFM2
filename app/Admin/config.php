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
        \DI\get('admin.prefix') // On r�cup�re la valeur d�finie pr�c�demment
    ),
    DashboardAction::class => \DI\create()->constructor(
        \DI\get(RendererInterface::class),
        \DI\get('admin.widgets')
    ),
];
