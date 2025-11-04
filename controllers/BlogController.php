<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Session;
use app\models\BlogModel;
use app\models\categoriesModel;
use JetBrains\PhpStorm\NoReturn;

class BlogController extends Controller{
    private Session $session;

    private BlogModel $blogModel;


    private CategoriesModel $categoriesModel;

    private $msg=[];

    public function __construct(){
        $this->session = Session::getInstance();
        $this->blogModel = new BlogModel();
        $this->categoriesModel = new CategoriesModel();

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
        $your_secret = "6LfNiAAsAAAAANEvg8Mf3UzbwxZE_quOdK7IfL_s";
        $client_captcha_response = $_POST['g-recaptcha-response'] ?? '';
        $user_ip = $_SERVER['REMOTE_ADDR'];

        $data = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$your_secret&response=$client_captcha_response&remoteip=$user_ip");
        $response = json_decode($data, true);
        $captchaSuccess = $response['success'] ?? false;

        if (!$captchaSuccess) {
            $this->msg = 'You need to check "I am not a robot".';
            header('Content-Type: application/json');
            echo json_encode([
                'successAdd' => false,
                'successCheck' => $captchaSuccess,
                'message' => $this->msg
            ]);
            exit();
        }

        if (!empty($_POST)) {
            $blog_title = $_POST['blog_title'] ?? '';
            $blog_body = $_POST['blog_body'] ?? '';
            $blog_picture = $_FILES['blog_picture'] ?? null;
            $blog_category = $_POST['blog_category'] ?? '';

            $result = $this->blogModel->createBlogs($blog_title, $blog_body, $blog_picture, $blog_category, $this->session->get('id'), $this->msg);

            header('Content-Type: application/json');
            echo json_encode([
                'successAdd' => $result,
                'successCheck' => $captchaSuccess,
                'message' => $this->msg
            ]);
            exit();
        }

        header('Content-Type: application/json');
        echo json_encode([
            'successAdd' => false,
            'successCheck' => $captchaSuccess,
            'message' => 'No form data received.'
        ]);
        exit();
    }


    public function edit(){

    }

    public function deleteBlog(){
        $cate_id = $_POST['blog_id'];

        header('Content-Type: application/json');
        echo json_encode([
            'success' => $this->blogModel->deleteBlogs($cate_id , $this->msg),
            'message' => $this->msg
        ]);
        exit();


        }







}




// call the db function and function to show the blogs


