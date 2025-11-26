<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Session;
use app\models\BlogModel;
use app\models\categoriesModel;

class BlogController extends Controller
{
    private Session $session;

    private BlogModel $blogModel;


    private CategoriesModel $categoriesModel;

    private $msg = [];

    public function __construct()
    {
        $this->session = Session::getInstance();
        $this->blogModel = new BlogModel();
        $this->categoriesModel = new CategoriesModel();
    }


    public function showBlogs($queryParameters = []): string
    {

        if (isset($_GET['page']) && (int)$_GET['page'] ) {

            if($_GET['page'] < 1)
                $this->redirect('blog?page=1');

            preg_match('/^([0-9]*)([\w\-]*)/',$_GET['page'] , $matches);

            if(!empty($matches[2])) {
                if (!empty($queryParameters))
                $this->redirect('blog/'.$queryParameters.'?page=' . $matches[1]);
                else
                    $this->redirect('blog?page='.$matches[1]);
            }

            $page =  $matches[0];
            $start = ($page - 1) * 9;

            // for categories
            if (!empty($queryParameters)) {
                $blogs = $this->blogModel->getBlogsByCategoryWithLimit($queryParameters, $start, 9);
                return $this->render('blog.view',
                    [
                        'heading' => 'Blog',
                        'results' => $blogs,
                        'categories' => $this->categoriesModel->getCategories(),
                        'pages' => ceil(count($this->blogModel->getBlogsByCategoryWithLimit($queryParameters)) / 9),
                        'category' => $queryParameters,
                    ]
                );
            }
            // for pagination
            else {
                return $this->render('blog.view',
                    [
                        'heading' => 'Blog',
                        'results' => $this->blogModel->getBlogsByRowNumber($start, 9),
                        'categories' => $this->categoriesModel->getCategories(),
                        'pages' => ceil(count($this->blogModel->getAllBlogs()) / 9),


                    ]
                );
            }
        }
        $this->redirect('blog?page=1');
    }






    public function createNewBlog()
    {
        if ($this->isPost()) {
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
                    'message' => $this->msg,
                ]);
                exit();
            }


            $blog_title = $_POST['blog_title'] ?? '';
            $blog_body = $_POST['blog_body'] ?? '';
            $blog_picture = $_FILES['blog_picture'] ?? null;
            $blog_category = $_POST['blog_category'] ?? '';

            $result = $this->blogModel->createBlogs($blog_title, $blog_body, $blog_picture, $blog_category, $this->session->get('id'), $this->msg);

            header('Content-Type: application/json');
            echo json_encode([
                'successAdd' => $result,
                'successCheck' => $captchaSuccess,
                'message' => $this->msg,
            ]);
            exit();


        } else
            $this->redirect('blog?page=1');
    }


    public function edit()
    {

    }

    public function deleteBlog()
    {
        if ($this->isPost()) {
            $cate_id = $_POST['blog_id'];
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $this->blogModel->deleteBlogs($cate_id, $this->msg),
                'message' => $this->msg
            ]);
            exit();
        } else
            $this->redirect('blog?page=1');

    }
}





// call the db function and function to show the blogs


