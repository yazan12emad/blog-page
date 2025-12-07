<?php

namespace app\models;

use app\core\DataBase;
use app\core\Model;

class HomeModel extends Model
{
    private Database $db;


    public function __construct(){
        $this->db = DataBase::getInstance();

    }

    public function getBlogs(){
        return $this->db->query("SELECT * FROM blog INNER JOIN categories ON blog.blog_category = categories.cate_id
         WHERE blog_status = 'live' LIMIT 0,5   ")->fetchAll();
    }

}