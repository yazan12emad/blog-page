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

    public function showBlogs($category = null): string
    {
        // To prevent the user from retrieving all existing blogs at once
//        if(!isset($_GET['page'])) {
//
//            $this->redirect('blog?page=1');
//
//        }


        $page = $_GET['page'] ?? 1 ;

            // the number action(page) must be: 1- int / 2 bigger than 0

        if (!filter_var($page, FILTER_VALIDATE_INT) || $page < 0) {
            $page =1 ;
        }



        $numberOfAllowBlogsInPage = 6;

        // to return the blogs that related to these category , for set up the pagination pages

            $numberOfPagesInPagination = !empty($category)
                ? ceil(count($this->blogModel->getBlogsByCategoryWithLimit($category)) / $numberOfAllowBlogsInPage)
                : ceil(count($this->blogModel->getAllBlogs()) / $numberOfAllowBlogsInPage);
        ;

                $page = $page > $numberOfPagesInPagination ? $numberOfPagesInPagination :$page ;


            $start = ($page - 1) * $numberOfAllowBlogsInPage;

            $blogs = !empty($category)
                ? $this->blogModel->getBlogsByCategoryWithLimit($category, $start, $numberOfAllowBlogsInPage)
                : $this->blogModel->getBlogsByRowNumber($start, $numberOfAllowBlogsInPage);


        return $this->render('blog.view',
                [
                    'heading' => 'Blog',
                    'results' => $blogs,
                    'categories' => $this->categoriesModel->getCategories(),
                    'pages' => $numberOfPagesInPagination,
                    'category' => $category,
                ]
            );
            }


    public function createNewBlog()
    {
        if ($this->isPost()) {


            $captchaSuccessResponse = $this->blogModel->captcha_response();
            if (!$captchaSuccessResponse) {
                $this->msg = 'You need to check "I am not a robot".';
                header('Content-Type: application/json');
                echo json_encode([
                    'successAdd' => false,
                    'successCheck' => $captchaSuccessResponse,
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
                'successCheck' => $captchaSuccessResponse,
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


