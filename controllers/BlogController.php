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

    private $message = [];

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

        $captchaSuccessResponse = $this->blogModel->captchaResponse();
        if (!$captchaSuccessResponse) {
            $this->jsonResponse([
                'successCreateBlog' => false,
                'successCheckCaptch' => $captchaSuccessResponse,
                'message' => 'You need to check "I am not a robot".',
            ]);
        }

        $blogFullInputData = array_merge($this->post() , ['author_id' => $this->session->get('id')]);

        $result = $this->blogModel->createBlogs($blogFullInputData, $this->message);
        $this->jsonResponse([
            'successCreateBlog' => $result,
            'successCheckCaptch' => $captchaSuccessResponse,
            'message' => $this->message,
        ]);
    }



    public function updateBlog()
    {
        if (!$this->isPost()) {
            $this->redirect('blog');
        }

        $captchaSuccessResponse = $this->blogModel->captchaResponse();
        if (!$captchaSuccessResponse) {
            $this->jsonResponse([
                'successUpdate' => false,
                'successCheckCaptch' => $captchaSuccessResponse,
                'message' => 'You need to check "I am not a robot".',
            ]);
        }

        $blogFullInputData = array_merge($this->post() , ['author_id' => $this->session->get('id')]);

        $result = $this->blogModel->validateUpdateBlogData($blogFullInputData, $this->message);
        $this->jsonResponse([
            'successUpdate' => $result,
            'successCheckCaptch' => $captchaSuccessResponse,
            'message' => $this->message,
        ]);



    }

    public function deleteBlog()
    {
        if (!$this->isPost()) {
            $this->redirect('blog');
        }

        $cate_id = $this->post('blog_id');
        $this->jsonResponse([
            'success' => $this->blogModel->deleteBlogs($cate_id, $this->message),
            'message' => $this->message,
        ]);
    }
}







