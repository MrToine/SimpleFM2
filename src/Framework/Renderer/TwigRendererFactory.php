<?php
namespace Framework\Renderer;

use Twig\Extension\DebugExtension;
use Utils\RouterTwigExtension;
use Psr\Container\ContainerInterface;

class TwigRendererFactory
{
    /**
     * Cr�e une instance de TwigRenderer en utilisant le conteneur de d�pendances
     *
     * @param ContainerInterface $container Le conteneur de d�pendances
     * @return TwigRenderer L'instance de TwigRenderer cr��e
     */
    public function __invoke(ContainerInterface $container): TwigRenderer
    {
        // R�cup�re le chemin des vues depuis la configuration
        $viewPath = $container->get('views.path');
        
        // Cr�e un loader Twig pour charger les fichiers de vue � partir du chemin des vues
        $loader = new \Twig\Loader\FilesystemLoader($viewPath);
        
        // Cr�e une instance de l'environnement Twig
        $twig = new \Twig\Environment($loader, ['debug' => true]);
        $twig->addExtension(new DebugExtension());
        
        // Ajoute les extensions Twig du conteneur s'il y en a
        if ($container->has('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($extension);
            }
        }

        // Retourne une nouvelle instance de TwigRenderer
        return new TwigRenderer($twig);
    }
}
