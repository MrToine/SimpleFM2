<?php
namespace Framework\Database;

use Pagerfanta\Adapter\AdapterInterface;

class PaginatedQuery implements AdapterInterface
{

    /**
     *
     * @var array
     */
    private $pdo;

    /**
     *
     * @var array
     */
    private $query;

    /**
     *
     * @var array
     */
    private $countQuery;

    /**
     * Class Qui g�re la pagination
     * @param \PDO $pdo
     * @param string $query requête permettant de retourner X r�sultats
     * @param string $countQuery requ�te permettant de compter le nombre de r�ultats total
     */

    public function __construct(\PDO $pdo, string $query, string $countQuery)
    {
        $this->pdo = $pdo;
        $this->query = $query;
        $this->countQuery = $countQuery;
    }

    /**
     *
     * Retourne le nombre de résultats pour la liste
     *
     * @phpstan-return int<0, max>
     */
    public function getNbResults(): int
    {
        return $this->pdo->query($this->countQuery)->fetchColumn();
    }

    /**
     *
     * Retourne une partie des résultats repr�sentant la page actuelle des �l�ments dans la liste.
     *
     * @phpstan-param int<0, max> $offset
     * @phpstan-param int<0, max> $length
     *
     * @return iterable<array-key, T>
     */
    public function getSlice(int $offset, int $length): iterable
    {
        $statement = $this->pdo->prepare($this->query . ' LIMIT :offset, :length');
        $statement->bindParam('offset', $offset, \PDO::PARAM_INT);
        $statement->bindParam('length', $length, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
