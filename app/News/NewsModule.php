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
	public function __construct(string $prefix, Router $router, RendererInterface $renderer)
	{
        $renderer->addPath('news', __DIR__ . '/Views');

        $router->get($prefix, NewsAction::class, 'news.index');
        $router->get($prefix . '/news/{slug:[a-z\0-9]+}-{id:[0-9]+}', NewsAction::class, 'news.view');
	}
}