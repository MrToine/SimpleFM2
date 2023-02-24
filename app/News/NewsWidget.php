<?php
namespace App\News;
use App\Admin\AdminWidgetInterface;
use Framework\Renderer\RendererInterface;

class NewsWidget implements AdminWidgetInterface
{

    private $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function render(): string
    {
        return $this->renderer->render('@news/admin/widget');
    }
}