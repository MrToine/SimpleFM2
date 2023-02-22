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
    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Ajoute un chemin pour charger les vues
     * @param string $namespace
     * @param null|string $path
     * @return void
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        $this->twig->getLoader()->addPath($path, $namespace);
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
        return $this->twig->render($view . '.html.twig', $params);
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
        $this->twig->addGlobal($key, $value);
    }
}
