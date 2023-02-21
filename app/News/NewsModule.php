<?php
namespace App\News;

use App\News\Actions\NewsAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;

/**
 * Classe Principal du module qui extends de la classe maitresse <Module>.
 * Permet de configurer et de faire fonctionner correctement le module.
 */
class NewsModule extends Module
{
	/**
	 * Constantes qui définissent les chemins vers :
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
	 * Constructeur du module. On à besoin de 3 arguments :
     *
     * @param string $prefix - défini dans le fichier config qui génére la route utilisé pour le module.
     * @param Router $router - permet de récupérer les informations du routeur
	 * @param RendererInterface $renderer - Utiliser pour la génération du rendu
	 */
	public function __construct(string $prefix, Router $router, RendererInterface $renderer)
	{
        $renderer->addPath('news', __DIR__ . '/views');

        $router->get($prefix, NewsAction::class, 'news.index');
        $router->get($prefix . '/news/{slug}', NewsAction::class, 'news.view');
	}
}