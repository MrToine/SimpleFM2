<?php

namespace Framework\Renderer;

interface RendererInterface
{
    /**
     * Ajoute un chemin pour charger les vues
     * @param string $namespace
     * @param null|string $path
     * @return void
     */
    public function addPath(string $namespace, ?string $path = null): void;

    /**
     * Permet de rendre une vue
     * Le chemin peut être préciser avec des namespace rajoutés via addPath()
     * $this->render('@blog/view');
     * $this->render('view');
     *
     * @param string $view
     * @param array $params
     * @return bool|string
     */
    public function render(string $view, array $params = []): string;

    /**
     * Permet de rajouter des variables globales à toute les vues
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addGlobal(string $key, $value): void;
}
