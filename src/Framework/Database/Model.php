<?php
namespace Framework\Database;

use Pagerfanta\Pagerfanta;

class Model
{
    /**
     * instance de PDO
     * @var mixed
     */
    protected $pdo;

    /**
     * Nom de la table en base de données
     * @var string
     */
    protected $table;

    /**
     * Entitée à utiliser
     * @var string|null
     */
    protected $entity;

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
     * @return Pagerfanta
     */
    public function findPaginated(int $perPage, int $currentPage): Pagerfanta
    {
        $query =  new PaginatedQuery(
            $this->pdo,
            $this->paginationQuery(),
            "SELECT COUNT(id) FROM {$this->table}",
            $this->entity
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    /**
     *
     * Récupère une liste clé => valeur des enregistrements en base de données
     *
     * */
    public function findList(): array
    {
        $results = $this->pdo
            ->query("SELECT id, name FROM {$this->table}")
            ->fetchAll(\PDO::FETCH_NUM);

        $list = [];
        foreach ($results as $result) {
            $list[$result[0]] = $result[1];
        }

        return $list;
    }

    protected function paginationQuery()
    {
        return "SELECT * FROM {$this->table}";
    }

    /**
     * Retourne un élement en fonction de l'id r�cup�rer
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        $query = $this->pdo
                ->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $query->execute([$id]);
        if ($this->entity) {
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }

        return $query->fetch() ?: null;
    }

    public function findAll(): array
    {
        $query = $this->pdo->query("SELECT * FROM {$this->table}");
        
        if ($this->entity) {
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        } else {
            $query->setFetchMode(\PDO::FETCH_OBJ);
        }
        
        return $query->fetchAll();
    }

    /**
     * Met à jour les champs dans la base de données
     * */
    public function update(int $id, array $params): bool
    {
        $fieldQuery = $this->buildFieldQuery($params);
        $params["id"] = $id;
        $statement = $this->pdo->prepare("UPDATE {$this->table} SET $fieldQuery WHERE id = :id");

        return $statement->execute($params);
    }

    public function insert(array $params):bool
    {
        $fields = array_keys($params);
        $values = join(', ', array_map(function ($field) {
            return ':' . $field;
        }, $fields));
        $fields = join(', ', $fields);
        $statement = $this->pdo->prepare("INSERT INTO {$this->table} ($fields) VALUES ($values)");

        return $statement->execute($params);
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $statement->execute([$id]);
    }

    private function buildFieldQuery($params)
    {
        return join(', ', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($params)));
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getPdo(): \PDO
    {
        return $this->pdo;
    }

    /**
     * Vérifie qu'un enregistrement existe
     * @param mixed $id
     * @return bool
     */
    public function exists($id): bool
    {
        $statement = $this->pdo->prepare("SELECT id FROM {$this->table} WHERE id = ?");
        $statement->execute(['id']);
        return $statement->fetchColumn() !== false;
    }
}
