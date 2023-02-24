<?php
namespace App\Pages\Models;

use \Framework\Database\Model;
use App\Pages\Entity\Page;

class PageModel extends Model
{
    protected $entity = Page::class;

    protected $table = 'pages'; 

    protected function paginationQuery()
    {
        return "SELECT n.id, n.name, n.content, c.name category_name
        FROM {$this->table} as n
        LEFT JOIN categories as c ON n.category_id = c.id
        ORDER BY created_date";
    }
}