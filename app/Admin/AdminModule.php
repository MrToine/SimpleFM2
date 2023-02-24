<?php

namespace App\Admin;

use App\Admin\Actions\DashboardAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;

class AdminModule extends Module
{
    const DEFINITIONS = __DIR__ . '/config.php';

    public function __construct(RendererInterface $renderer, Router $router, string $prefix)
    {
        $renderer->addPath('admin', __DIR__ . '/Views');
        $router->get($prefix, DashboardAction::class, 'admin');
    }
}