<?php

namespace app\models;

use app\core\DataBase;
use app\core\Model;

class BlogModel extends Model
{
    private Database $dataBase;

    private ValidationClass $validationClass;


    public function __construct()
    {
        $this->dataBase = DataBase::getInstance();
        $this->validationClass = new ValidationClass();

    }

    public function captchaResponse(){
        $yourSecret = "6LfNiAAsAAAAANEvg8Mf3UzbwxZE_quOdK7IfL_s";
        $clientCaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
        $userIp = $_SERVER['REMOTE_ADDR'];
        $data = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$yourSecret&response=$clientCaptchaResponse&remoteip=$userIp");
        $response = json_decode($data, true);
        return $response['success'] ?? false;
    }

    public function getAllBlogs(): array
    {
        return $this->dataBase->query("select blog.* , UsersInformation.userName , categories.cate_name  from blog
                    INNER join categories on blog.blog_category = categories.cate_id
                    INNER join UsersInformation on  UsersInformation.id = blog.author_id
                     ORDER BY blog.created_at DESC  ")->fetchAll();
    }

    public function getBlogsByRowNumber($start, $end): array
    {
        return $this->dataBase->query("SELECT * FROM blog INNER JOIN categories ON blog.blog_category = categories.cate_id
         WHERE blog_status = 'live' LIMIT $start, $end  ")->fetchAll();
    }


    public function getBlogsByCategoryWithLimit($category, $start = 0, $end = 0): array
    {
        if ($end !== 0)
            return $this->dataBase->query("select * from blog
                    INNER join categories on blog.blog_category = categories.cate_id 
                    where blog_status = 'live' && cate_name =:category LIMIT $start, $end ",
                [
                    'category' => $category
                ]
            )->fetchAll();
        else
            return $this->dataBase->query("select * from blog
                    INNER join categories on blog.blog_category = categories.cate_id 
                    where blog_status = 'live' && cate_name =:category ",
                [
                    'category' => $category
                ]
            )->fetchAll();
    }

    public function getBlogById($id)
    {
        $data = $this->dataBase->query("SELECT blog.* , UsersInformation.userName FROM blog INNER JOIN UsersInformation ON blog.author_id = UsersInformation.id WHERE blog.blog_id = :id ;", [
            'id' => $id
        ])->fetch(\PDO::FETCH_ASSOC);
        if ($data)
            return $data;
        else
            return false;
    }



    public function createBlogs($blogFullInputData , &$message = []): bool
    {
        if(!$this->blogValidationClass($blogFullInputData , $message)){
            return false;
        }

        $blog_picture_URL = (new UploadFiles)->addImg($_FILES['blog_picture']);

        if (!$blog_picture_URL) {
            $message = 'Cannot upload picture';
            return false;
        }  if ($this->insertBlog(
            $blogFullInputData['blog_title'],
            $blogFullInputData['blog_body'],
            $blog_picture_URL,
            $blogFullInputData['blog_category'],
            $blogFullInputData['author_id'],
            $message)) {
        return true;
    }

            return false;

    }

    public function insertBlog($blog_title, $blog_body, $blog_picture_URL, $blog_category, $author_id, &$message = []): bool
    {
        if ($this->dataBase->query('INSERT INTO 
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
            $message = 'Blog created';
            return true;
        } else {
            $message = 'Blog creation failed';
            return false;
        }

    }

    public function validateUpdateBlogData($blogFullInputData , &$message){
        if(!$this->blogValidationClass($blogFullInputData , $message)){
            return false;
        }

        $blogCurrentData = $this->getBlogById($blogFullInputData['blog_id']);

        if(!$blogCurrentData){
            $message = 'Blog not found';
            return false;
        }

        if(trim($blogFullInputData['blog_title']) !== $blogCurrentData['blog_title']){
            $this->updateBlogs($blogFullInputData['blog_id'] , 'blog_title' , trim($blogFullInputData['blog_title']) ,$message['name']);
        }
        if(trim($blogFullInputData['blog_body']) !== $blogCurrentData['blog_body']){
            $this->updateBlogs($blogFullInputData['blog_id'] , 'blog_body', trim($blogFullInputData['blog_body']) , $message['body']);
        }
        if(trim($blogFullInputData['blog_category']) !== $blogCurrentData['blog_category']){
            /////// errrorooreoorororrrroooooor //
            $this->updateBlogs($blogFullInputData['blog_id'] , 'blog_category' , $blogFullInputData['blog_category'] , $message['category']);
        }
        return true;
    }

    public function updateBlogs($blog_id, $key, $value, &$message): bool
    {
        if ($this->dataBase->query(
            "UPDATE blog SET $key = :value WHERE blog_id = :blog_id",
            [
                ':blog_id' => $blog_id,
                ':value' => $value
            ]
        )) {
            $message = 'The ' .$key .' updated';
            return true;
        }
            $message = 'Error updating blog';
            return false;

    }


    public function deleteBlogs($blog_id, &$message = []): bool
    {
        if ($this->dataBase->query("DELETE FROM blog where blog_id = :blog_id ", [
            ':blog_id' => $blog_id
        ])) {
            $message = ' Blog deleted successfully';
            return true;
        } else {
            $message = 'Blog deletion failed';
            return false;
        }

    }

    public function blogValidationClass($blogFullInputData , &$message){
        if(strlen(trim($blogFullInputData['blog_title'])) < 1 ) {
            $message = 'The blog title is required.';
            return false;
        }
        if(strlen(trim($blogFullInputData['blog_title'])) < 5 ) {
            $message = 'The blog title must be more than 5 characters.';
            return false;
        }

        if(strlen(trim($blogFullInputData['blog_body'])) < 1 ) {
            $message = 'The blog title is required.';
            return false;
        }

        if(strlen(trim($blogFullInputData['blog_body'])) < 5 ) {
            $message = 'The blog title must be more than 5 characters.';
            return false;
        }

        if (empty($blogFullInputData['blog_category']) || !is_numeric($blogFullInputData['blog_category'])) {
            $message = 'Valid blog category is required.';
            return false;
        }
        if (empty($blogFullInputData['author_id']) || !is_numeric($blogFullInputData['author_id'])) {
            $message = 'Valid blog category is required.';
            return false;
        }

        return true;
    }










}