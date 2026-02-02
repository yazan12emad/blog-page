<?php

namespace app\models;

use app\core\DataBase\DataBase;
use app\core\Model;
use PDOException;

class HomeModel extends Model
{
    public Database $database;


    public function __construct(){
        $this->database = DataBase::getInstance();

    }

    public function getBlogs(){
        try {
            return $this->database->query("SELECT * FROM blog INNER JOIN categories ON blog.blog_category = categories.cate_id
         WHERE blog_status = 'live' LIMIT 0,5   ")->fetchAll();
        }
        catch(PDOException $e){
            echo 'Error in upload blogs in home page';
            return false;
        }
    }

}