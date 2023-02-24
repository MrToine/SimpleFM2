<?php
namespace App\Pages;
use App\Admin\AdminWidgetInterface;
use Framework\Renderer\RendererInterface;

class PagesWidget implements AdminWidgetInterface
{

    private $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function render(): string
    {
        return $this->renderer->render('@pages/admin/widget');
    }
}