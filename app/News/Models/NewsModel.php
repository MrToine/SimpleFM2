<?php
namespace App\News\Models;

class NewsModel
{
    /**
     * instance de PDO
     * @var mixed
     */
    private $pdo;

    /**
     * Summary of __construct
     * @param \PDO $pdo 
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retourne la totalité de la table et la pagine
     * @return array
     */
    public function findPaginated(): array
    {
        return $this->pdo
			    ->query('SELECT * FROM news ORDER BY created_date DESC LIMIT 0, 10')
			    ->fetchAll();
    }

    /**
     * Retourne une news en fonction de l'id récupérer
     * @param int $id 
     * @return mixed
     */
    public function find(int $id): \StdClass
    {
		$query = $this->pdo
				->prepare('SELECT * FROM news WHERE id = ?');
		$query->execute([$id]);

        return $query->fetch();
    }
}