<?php
namespace App\News\Models;
use Faker\Guesser\Name;
use Framework\Database\PaginatedQuery;
use Pagerfanta\Pagerfanta;
use App\News\Entity\News;

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
     * Retourne la totalit� de la table et la pagine
     * @return Pagerfanta
     */
    public function findPaginated(int $perPage, int $currentPage): Pagerfanta
    {
        $query =  new PaginatedQuery(
            $this->pdo,
            'SELECT * FROM news',
            'SELECT COUNT(id) FROM news'
            );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    /**
     * Retourne une news en fonction de l'id r�cup�rer
     * @param int $id
     * @return News
     */
    public function find(int $id): News
    {
		$query = $this->pdo
				->prepare('SELECT * FROM news WHERE id = ?');
		$query->execute([$id]);
        $query->setFetchMode(\PDO::FETCH_CLASS, News::class);

        return $query->fetch();
    }

    /**
     * Met à jour les champs dans la base de données
     * */
    public function update(int $id, array $params): bool
    {
        $fieldQuery = $this->buildFieldQuery($params);
        $params["id"] = $id;
        $statement = $this->pdo->prepare("UPDATE news SET $fieldQuery WHERE id = :id");

        return $statement->execute($params);
    }

    public function insert(array $params):bool
    {
        $fields = array_keys($params);
        $values = array_map(function ($field) {
            return ':' . $field;
        }, $fields);

        $statement = $this->pdo->prepare("INSERT INTO news (" . join(',', $fields) .") VALUES (" . join(',', $values) . ")");

        return $statement->execute($params);
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM news WHERE id = ?');
        return $statement->execute([$id]);
    }

    private function buildFieldQuery($params)
    {
        return join(', ', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($params)));
    }
}