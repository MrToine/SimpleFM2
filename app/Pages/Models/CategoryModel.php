<?php
namespace App\Pages\Models;

use \Framework\Database\Model;
use App\News\Entity\News;

class CategoryModel extends Model
{

    protected $table = 'categories';

    protected function paginationQuery()
    {
        return parent::paginationQuery() . " WHERE type = 'PAGE'  ORDER BY id DESC";
    }
}