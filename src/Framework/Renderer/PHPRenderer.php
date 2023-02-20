<?php

namespace Framework\Renderer;

/**
 * Classe qui permet de gérer la génération de la vue
 */
class PHPRenderer implements RendererInterface
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
     * On demande le chemin par défaut des views qui par défaut est null (il peut rester null)
     * @param null|string $defaultPath
     */
    public function __construct(?string $defaultPath = null)
    {
        if (!is_null($defaultPath)) {
            $this->addPath($defaultPath);
        }
    }

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
     * Le chemin peut être préciser avec des namespace rajoutés via addPath()
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
     * Permet de rajouter des variables globales à toute les vues
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
     * Permet de vérifier si il y un namespace
     * @param string $view
     * @return bool
     */
    private function hasNamespace(string $view): bool
    {
        return $view[0] === '@';
    }

    /**
     * Récupère le namespace de la vue
     * @param string $view
     * @return string
     */
    private function getNamespace(string $view): string
    {
        return substr($view, 1, strpos($view, '/') -1);
    }

    /**
     * Remplace le namespace '@' pour pouvoir récupérer la vue
     * @param string $view
     * @return array|string
     */
    private function replaceNamespace(string $view): string
    {
        $namespace = $this->getNamespace($view);
        return str_replace('@' . $namespace, $this->paths[$namespace], $view);
    }
}
