<?php

namespace Framework\Renderer;

class TwigRenderer implements RendererInterface
{
    /**
     * Contiens l'environnement de Twig
     * @var mixed
     */
    private $twig;

    /**
     * contiens le loader de Twig
     * @var mixed
     */
    private $loader;

    /**
     * Summary of __construct
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->loader = new \Twig\Loader\FilesystemLoader($path);
        $this->twig = new \Twig\Environment($this->loader, []);
    }

    /**
     * Ajoute un chemin pour charger les vues
     * @param string $namespace
     * @param null|string $path
     * @return void
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        $this->loader->addPath($path, $namespace);
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
        return $this->twig->render($view . '.html.twig', $params);
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
        $this->twig->addGlobal($key, $value);
    }
}
