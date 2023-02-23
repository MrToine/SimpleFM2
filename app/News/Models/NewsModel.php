<?php
namespace App\News\Models;

use \Framework\Database\Model;
use App\News\Entity\News;

class NewsModel extends Model
{
    protected $entity = News::class;

    protected $table = 'news'; 

    protected function paginationQuery()
    {
        return "SELECT n.id, n.name, n.content, c.name category_name
        FROM {$this->table} as n
        LEFT JOIN categories as c ON n.category_id = c.id
        ORDER BY created_date";
    }
}