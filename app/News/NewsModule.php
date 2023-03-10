<?php
namespace App\News;

use App\News\Actions\NewsAction;
use App\News\Actions\AdminCategoryAction;
use App\News\Actions\AdminNewsAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Container\ContainerInterface;

/**
 * Classe Principal du module qui extends de la classe maitresse <Module>.
 * Permet de configurer et de faire fonctionner correctement le module.
 */
class NewsModule extends Module
{
	/**
	 * Constantes qui d?finissent les chemins vers :
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
	 * Constructeur du module. On ? besoin de 3 arguments :
     *
     * @param string $prefix - d?fini dans le fichier config qui g?n?re la route utilis? pour le module.
     * @param Router $router - permet de r?cup?rer les informations du routeur
	 * @param RendererInterface $renderer - Utiliser pour la g?n?ration du rendu
	 */
	public function __construct(ContainerInterface $container)
	{
		$newsPrefix = $container->get('news.prefix');

        $container->get(RendererInterface::class)->addPath('news', __DIR__ . '/Views');

		$router = $container->get(Router::class);

        $router->get($container->get('news.prefix'), NewsAction::class, 'news.index');
        $router->get("$newsPrefix/{slug:[a-z\-0-9]+}-{id:[0-9]+}", NewsAction::class, 'news.view');
        $router->get("$newsPrefix/category/{slug:[a-z\-0-9]+}", CategoryAction::class, 'news.category');

		if ($container->has('admin.prefix')) {
			/**
             * contient la liste des routes (CRUD => dans le router)
             */
			$prefix = $container->get('admin.prefix');

			$router->crud("$prefix/news", AdminNewsAction::class, 'admin.news');
			$router->crud("$prefix/news/categories", AdminCategoryAction::class, 'admin.news.categories');
        }
	}
}