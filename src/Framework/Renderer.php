<?php

namespace Framework;

use SebastianBergmann\Type\VoidType;

class Renderer
{
    /**
     * Namespace par defaut de l'application
     */
    const DEFAULT_NAMESPACE = "__MAIN";
    /**
     * liste des chemins dans un tableau
     * @var mixed
     */
    private $paths = [];

    /**
     * Variables globalement accessible pour toute les vues
     * @var array
     */
    private $globals = [];

    /**
     * Ajoute un chemin pour charger les vues
     * @param string $namespace
     * @param null|string $path
     * @return void
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        if (is_null($path)) {
            $this->paths[self::DEFAULT_NAMESPACE] = $namespace;
        } else {
            $this->paths[$namespace] = $path;
        }
    }

    /**
     * Permet de rendre une vue
     * Le chemin peut �tre pr�ciser avec des namespace rajout�s via addPath()
     * $this->render('@blog/view');
     * $this->render('view');
     *
     * @param string $view
     * @param array $params
     * @return bool|string
     */
    public function render(string $view, array $params = []): string
    {
        if ($this->hasNamespace($view)) {
            $path = $this->replaceNamespace($view) . '.php';
        } else {
            $path = $this->paths[self::DEFAULT_NAMESPACE] . DIRECTORY_SEPARATOR . $view . '.php';
        }

        ob_start();

        $renderer = $this;

        extract($this->globals);
        extract($params);

        require($path);
        return ob_get_clean();
    }

    /**
     * Permet de rajouter des variables globales � toute les vues
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addGlobal(string $key, $value): void
    {
        $this->globals[$key] = $value;
    }

    /**
     * Permet de v�rifier si il y un namespace
     * @param string $view
     * @return bool
     */
    private function hasNamespace(string $view): bool
    {
        return $view[0] === '@';
    }

    /**
     * R�cup�re le namespace de la vue
     * @param string $view
     * @return string
     */
    private function getNamespace(string $view): string
    {
        return substr($view, 1, strpos($view, '/') -1);
    }

    /**
     * Remplace le namespace '@' pour pouvoir r�cup�rer la vue
     * @param string $view
     * @return array|string
     */
    private function replaceNamespace(string $view): string
    {
        $namespace = $this->getNamespace($view);
        return str_replace('@' . $namespace, $this->paths[$namespace], $view);
    }
}
