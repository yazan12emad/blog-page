<?php

namespace app\models;

use app\core\DataBase;
use app\core\Model;
class BlogModel extends Model
{
    private Database $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    public function getAllBlogs(): array
    {
        return $this->db->query("select blog_title , blog_body , blog_picture , cate_name from blog
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



    public function getAllBlogsNoCondition(): array
    {
        return $this->db->query("SELECT * FROM blog")->fetchAll();
    }

    public function createBlogs($blog_title , $blog_body , $blog_picture , $blog_category ,$author_id, &$msg = []):bool
    {
        $blog_picture_URL = (new UploadFiles)->addImg($blog_picture);
        if(!$blog_picture_URL) {
            $msg['error'] = 'Cannot upload picture';
            return false;
        }

        else if($this->db->query('INSERT INTO 
                        blog (blog_title , blog_body , blog_picture ,blog_category  ,author_id ) 
                            VALUES (:blog_title , :blog_body , :blog_picture ,:blog_category , :author_id)',
                [
                    ':blog_title' => $blog_title,
                    ':blog_body' => $blog_body,
                    ':blog_picture' => $blog_picture_URL,
                    ':blog_category' => $blog_category,
                    ':author_id' => $author_id
                ]
            )){
                $msg['success'] = 'Blog created';
                return true;
            }
            else {
             $msg['error'] = 'Blog creation failed';
                return false;
            }

    }

    public function updateBlogs()
    {


    }

    public function deleteBlogs()
    {


    }




}