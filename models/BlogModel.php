<?php

namespace app\models;

use app\core\DataBase;
use app\core\Model;
use app\models\ValidationClass;

class BlogModel extends Model
{
    private Database $db;

    private ValidationClass $validationClass;


    public function __construct()
    {
        $this->db = DataBase::getInstance();
        $this->validationClass = new ValidationClass();

    }

    public function getAllBlogs(): array
    {
        return $this->db->query("select * from blog
                    INNER join categories on blog.blog_category = categories.cate_id 
                    where blog_status = 'live'   ")->fetchAll();
    }

    public function getBlogsByCategory($category): array
    {
        return $this->db->query("select * from blog
                    INNER join categories on blog.blog_category = categories.cate_id 
                    where blog_status = 'live' && cate_name =:category " ,
            [
             'category' => $category
        ]
        )->fetchAll();
    }

    public function getBlogById($id): array{
        return $this->db->query("select * from blog where blog_id =:id ",[
            'id' => $id
        ])->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAllBlogsNoCondition(): array
    {
        return $this->db->query("SELECT * FROM blog INNER JOIN categories on blog.blog_category = categories.cate_id  ")->fetchAll();
    }

    public function createBlogs($blog_title , $blog_body , $blog_picture , $blog_category ,$author_id, &$msg = []):bool
    {
        if(!$this->validationClass->notEmpty($blog_title ))
        {
            $msg = 'The blog title is required.';
            return false;
        }
        else if(!$this->validationClass->notEmpty($blog_body))
        {
            $msg = 'The blog body is required.';
            return false;
        }
        $blog_picture_URL = (new UploadFiles)->addImg($blog_picture);
        if(!$blog_picture_URL) {
            $msg = 'Cannot upload picture';
            return false;
        }

        else if($this->insertBlog($blog_title , $blog_body , $blog_picture_URL , $blog_category ,$author_id, $msg ))
                 return true;

        else
            return false;

    }

    public function insertBlog($blog_title , $blog_body , $blog_picture_URL , $blog_category ,$author_id, &$msg = []):bool{
        if( $this->db->query('INSERT INTO 
                        blog (blog_title , blog_body , blog_picture ,blog_category  ,author_id ) 
                            VALUES (:blog_title , :blog_body , :blog_picture ,:blog_category , :author_id)',
            [
                ':blog_title' => $blog_title,
                ':blog_body' => $blog_body,
                ':blog_picture' => $blog_picture_URL,
                ':blog_category' => $blog_category,
                ':author_id' => $author_id
            ]
        )) {
            $msg = 'Blog created';
            return true;
        }
        else {
            $msg = 'Blog creation failed';
            return false;
        }

    }

    public function updateBlogs($blog_id , $key , $value , &$msg = []):bool
    {
        if($this->db->query("UPDATE blog SET $key =:value WHERE blog_id =:blog_id " ,
        [
            ':blog_id' => $blog_id,
            ':value' => $value

        ])){
            $msg = 'blog updated';
        return true;

        } else
        $msg = 'error update blog';

        return false;

    }

    public function deleteBlogs($blog_id , &$msg=[]):bool
    {
         if($this->db->query("DELETE FROM blog where blog_id = :blog_id " , [
            ':blog_id' => $blog_id
        ])) {
             $msg =' Blog deleted successfully';
             return true;
         }
         else {
             $msg = 'Blog deletion failed';
             return false;
}


    }




}