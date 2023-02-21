<?php

namespace Framework\Renderer;

/**
 * Classe qui permet de g�rer la g�n�ration de la vue
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
     * Constructeur qui permet de d�finir le chemin par d�faut pour les vues
     * @param null|string $defaultPath
     */
    public function __construct(?string $defaultPath = null)
    {
        if (!is_null($defaultPath)) {
            $this->addPath($defaultPath);
        }
    }

    /**
     * M�thode pour ajouter un chemin pour charger les vues
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
     * M�thode pour rendre une vue
     * Le chemin peut �tre pr�cis� avec des namespaces ajout�s via addPath()
     * ex : $this->render('@blog/view');
     * ex : $this->render('view');
     *
     * @param string $view Le chemin de la vue � rendre
     * @param array $params Les variables pass�es � la vue
     * @return string La vue rendue
     */
    public function render(string $view, array $params = []): string
    {
        // Si le chemin de la vue contient un namespace, on le remplace par le chemin correspondant
        if ($this->hasNamespace($view)) {
            $path = $this->replaceNamespace($view) . '.php';
        } else {
            // Sinon, on utilise le chemin par d�faut
            $path = $this->paths[self::DEFAULT_NAMESPACE] . DIRECTORY_SEPARATOR . $view . '.php';
        }

        // On commence � "capturer" la sortie pour �viter qu'elle soit envoy�e au navigateur
        ob_start();

        // On d�finit le renderer pour permettre l'utilisation de la m�thode render() dans la vue
        $renderer = $this;

        // On extrait les variables globales et locales pour qu'elles soient accessibles dans la vue
        extract($this->globals);
        extract($params);

        // On inclut la vue
        require($path);

        // On arr�te la "capture" de la sortie et on la retourne
        return ob_get_clean();
    }

    /**
     * M�thode pour ajouter des variables globales � toutes les vues
     *
     * @param string $key La cl� de la variable
     * @param mixed $value La valeur de la variable
     * @return void
     */
    public function addGlobal(string $key, $value): void
    {
        $this->globals[$key] = $value;
    }

    /**
     * M�thode pour v�rifier si le chemin de la vue contient un namespace
     * @param string $view Le chemin de la vue
     * @return bool Vrai si


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
