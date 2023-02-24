<?php
namespace App\News\Models;

use \Framework\Database\Model;
use App\News\Entity\News;
use Framework\Database\PaginatedQuery;
use Pagerfanta\Pagerfanta;

class NewsModel extends Model
{
    protected $entity = News::class;

    protected $table = 'news';

    public function findPaginatedPublic(int $perPage, int $currentPage): Pagerfanta
    {
        $query =  new PaginatedQuery(
           $this->pdo,
                "SELECT n.*, c.name as category_name, c.slug as category_slug
                FROM news as n
                LEFT JOIN categories as c ON c.id = n.category_id
                ORDER BY n.created_date DESC",
                "SELECT COUNT(id) FROM {$this->table}",
           $this->entity
       );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    protected function paginationQuery()
    {
        return "SELECT n.id, n.name, n.content, c.name category_name
        FROM {$this->table} as n
        LEFT JOIN categories as c ON n.category_id = c.id
        ORDER BY created_date";
    }
}