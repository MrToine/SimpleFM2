<?php
namespace Framework\Renderer;

use Framework\Router\RouterTwigExtension;
use Psr\Container\ContainerInterface;

class TwigRendererFactory
{
    /**
     * Crée une instance de TwigRenderer en utilisant le conteneur de dépendances
     *
     * @param ContainerInterface $container Le conteneur de dépendances
     * @return TwigRenderer L'instance de TwigRenderer créée
     */
    public function __invoke(ContainerInterface $container): TwigRenderer
    {
        // Récupère le chemin des vues depuis la configuration
        $viewPath = $container->get('views.path');
        
        // Crée un loader Twig pour charger les fichiers de vue à partir du chemin des vues
        $loader = new \Twig\Loader\FilesystemLoader($viewPath);
        
        // Crée une instance de l'environnement Twig
        $twig = new \Twig\Environment($loader);
        
        // Ajoute les extensions Twig du conteneur s'il y en a
        if ($container->has('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($extension);
            }
        }

        // Retourne une nouvelle instance de TwigRenderer
        return new TwigRenderer($loader, $twig);
    }
}
