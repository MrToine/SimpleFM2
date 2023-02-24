<?php
namespace App\Pages;

use App\Pages\Actions\PagesAction;
use App\Pages\Actions\AdminCategoryAction;
use App\Pages\Actions\AdminPagesAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Container\ContainerInterface;

/**
 * Classe Principal du module qui extends de la classe maitresse <Module>.
 * Permet de configurer et de faire fonctionner correctement le module.
 */
class PagesModule extends Module
{
	/**
	 * Constantes qui d�finissent les chemins vers :
     * Config du module
     * Migrations de la db
     * Seeds pour les tests db
	 */

	/**
	 * lien vers le fichier config
	 */
	const DEFINITIONS = __DIR__ . '/config.php';

	/**
	 * lien vers le dossier de migrations
	 */
	const MIGRATIONS = __DIR__ . '/db/migrations';

	/**
	 * lien vers les seeds (test db)
	 */
	const SEEDS = __DIR__ . '/db/seeds';

	/**
	 * Constructeur du module. On � besoin de 3 arguments :
     *
     * @param string $prefix - d�fini dans le fichier config qui g�n�re la route utilis� pour le module.
     * @param Router $router - permet de r�cup�rer les informations du routeur
	 * @param RendererInterface $renderer - Utiliser pour la g�n�ration du rendu
	 */
	public function __construct(ContainerInterface $container)
	{
		$pagesPrefix = $container->get('pages.prefix');

        $container->get(RendererInterface::class)->addPath('pages', __DIR__ . '/Views');

		$router = $container->get(Router::class);

        $router->get($container->get('pages.prefix'), PagesAction::class, 'pages.index');
        $router->get("$pagesPrefix/{slug:[a-z\-0-9]+}-{id:[0-9]+}", PagesAction::class, 'pages.view');

		if ($container->has('admin.prefix')) {
			/**
             * contient la liste des routes (CRUD => dans le router)
             */
			$prefix = $container->get('admin.prefix');

			$router->crud("$prefix/pages", AdminPagesAction::class, 'admin.pages');
			$router->crud("$prefix/pages/categories", AdminCategoryAction::class, 'admin.pages.categories');
        }
	}
}