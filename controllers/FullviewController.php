<?php

namespace app\controllers;

use app\core\Controller;
use app\models\BlogModel;

class FullViewController extends Controller
{
    private BlogModel $blogModel;
    public function __construct(){
        $this->blogModel = new BlogModel();
    }
public function showBlog($blogId)
{

    $blogData = $this->blogModel->getBlogById($blogId);
        if($blogData)
        return $this->render('FullBlog.view' , ['heading' =>$blogData['blog_title'] , 'blogData' => $blogData]);
        else
            return $this->render('FullBlog.view' , ['heading'=> 'there is no blog available ' , 'blogData' => FALSE]);
}


}