<?php
namespace App\News\Models;

use \Framework\Database\Model;
use App\News\Entity\News;

class CategoryModel extends Model
{

    protected $table = 'categories';

    protected function paginationQuery()
    {
        return parent::paginationQuery() . " ORDER BY id DESC";
    }
}