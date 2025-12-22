<?php

// simplify done

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
        $page = $_GET['page'] ?? 1;
        if (!filter_var($page, FILTER_VALIDATE_INT) || $page < 0) {
            $page = 1;
        }

        $numberOfAllowBlogsInPage = 6;
        if (isset($_GET['limit'])) {
            $limit = (int) $_GET['limit'];
            $numberOfAllowBlogsInPage = max(1 , min($limit , 30)) ;
        }

        $numberOfPagesInPagination = !empty($category)
            ? ceil(count($this->blogModel->getBlogsByCategoryWithLimit($category)) / $numberOfAllowBlogsInPage)
            : ceil(count($this->blogModel->getAllBlogs()) / $numberOfAllowBlogsInPage);;

        $page = min($page, $numberOfPagesInPagination);
        $start = ($page - 1) * $numberOfAllowBlogsInPage;
        $blogs = !empty($category)
            ? $this->blogModel->getBlogsByCategoryWithLimit($category, $start, $numberOfAllowBlogsInPage)
            : $this->blogModel->getBlogsByRowNumber($start, $numberOfAllowBlogsInPage);

        return $this->render('blog.view', [
            'heading' => 'Blog',
            'results' => $blogs,
            'categories' => $this->categoriesModel->getCategories(),
            'pages' => $numberOfPagesInPagination,
            'category' => $category,
        ]);
    }


    public function createNewBlog()
    {
        if (!$this->isPost()) {
            $this->redirect('blog');
        }

        $captchaSuccessResponse = $this->blogModel->captcha_response();
        if (!$captchaSuccessResponse) {
            $this->jsonResponse([
                'successAdd' => false,
                'successCheck' => $captchaSuccessResponse,
                'message' => 'You need to check "I am not a robot".',
            ]);
        }

        $blog_title = $_POST['blog_title'] ?? null;
        $blog_body = $_POST['blog_body'] ?? null;
        $blog_picture = $_FILES['blog_picture'] ?? null;
        $blog_category = $_POST['blog_category'] ?? null;

        $result = $this->blogModel->createBlogs($blog_title, $blog_body, $blog_picture, $blog_category, $this->session->get('id'), $this->msg);
        $this->jsonResponse([
            'successAdd' => $result,
            'successCheck' => $captchaSuccessResponse,
            'message' => $this->msg,
        ]);
    }


    public function edit()
    {

    }

    public function deleteBlog()
    {
        if (!$this->isPost()) {
            $this->redirect('blog');
        }

        $cate_id = $this->post('blog_id');
        $this->jsonResponse([
            'success' => $this->blogModel->deleteBlogs($cate_id, $this->msg),
            'message' => $this->msg,
        ]);
    }
}







