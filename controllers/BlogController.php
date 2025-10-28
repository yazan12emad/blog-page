<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Session;
use app\models\BlogModel;
use app\models\categoriesModel;
use app\models\ValidationClass;
use JetBrains\PhpStorm\NoReturn;

class BlogController extends Controller{
    private Session $session;

    private BlogModel $blogModel;

    private ValidationClass $validationClass;

    private CategoriesModel $categoriesModel;

    private $msg=[];

    public function __construct(){
        $this->session = Session::getInstance();
        $this->blogModel = new BlogModel();
        $this->validationClass = new ValidationClass();
        $this->categoriesModel = new CategoriesModel();

    }

    #[NoReturn]
    public function reloadPage(): void
    {
         $this->redirect('blog');
    }

    public function showBlogs():string
    {
        if(isset($_GET['category'])){
            return $this->render('blog.view',
                [
                    'heading'=>'Blog' ,
                    'results'=>$this->blogModel->getBlogsByCategory($_GET['category']),
                    'categories'=> $this->categoriesModel->getCategories(),
            ]
            );
    }
        else
        return  $this->render('blog.view' ,
            [
                'heading'=>'Blog' ,
                'results'=>$this->blogModel->getAllBlogs(),
                'categories'=>$this->categoriesModel->getCategories(),

            ]
        );
    }


    public function createNewBlog()
    {
        if(!empty($_POST)) {
            $blog_title = $_POST['blog_title'];
            $blog_body = $_POST['blog_body'];
            $blog_picture = $_FILES['blog_picture'];
            $blog_category = $_POST['blog_category'];

            header('Content-Type: application/json');
            echo json_encode([
                'success' => $this->blogModel->createBlogs($blog_title, $blog_body, $blog_picture, $blog_category, $this->session->get('id'), $this->msg),
                'message' => $this->msg
            ]);
            exit();
        }
        else
        $this->reloadPage();

    }

    public function edit(){

    }

    public function deleteBlog(){


    }






}




// call the db function and function to show the blogs


