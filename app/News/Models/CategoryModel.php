<?php
namespace App\News\Models;

use \Framework\Database\Model;

class CategoryModel extends Model
{

    protected $table = 'categories';

    protected function paginationQuery()
    {
        return parent::paginationQuery() . " WHERE type = 'NEWS'  ORDER BY id DESC";
    }

    public function findAllCat(): array
    {
        $query = $this->pdo->prepare("SELECT * FROM categories WHERE type = 'NEWS'");
        if($this->entity)
        {
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }
        else
        {
            $query->setFetchMode(\PDO::FETCH_OBJ);
        }

        return $query->fetchAll();
    }
}