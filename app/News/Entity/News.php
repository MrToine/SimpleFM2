<?php
namespace App\News\Entity;

class News
{
    public $id;

    public $name;

    public $slug;

    public $content;

    public $created_date;

    public $updated_date;

    public $category_name;

    public function __construct()
    {
        if($this->created_date)
        {
            $this->created_date = new \DateTime($this->created_date);
        }

        if($this->updated_date)
        {
            $this->updated_date = new \DateTime($this->updated_date);
        }
    }
}