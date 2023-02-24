<?php
namespace App\Admin;

interface AdminWidgetInterface
{
    public function render(): string;
}