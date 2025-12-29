<?php


namespace app\controllers;

use app\core\Controller;
use app\models\AdminSiteModel;
use app\core\Session;

class AdminSite extends Controller
{
    private AdminSiteModel $adminSiteModel;

    private session $session;
    private array $usersData;
    private array $categoriesData;
    private array $blogData;

    private $message = null;

    public function __construct()
    {
        $this->adminSiteModel = new AdminSiteModel();
        $this->session = Session::getInstance();

        $this->usersData = $this->adminSiteModel->getUsers();
        $this->categoriesData = $this->adminSiteModel->getCategories();
        $this->blogData = $this->adminSiteModel->getBlogs();

    }

    public function showSite()
    {
        if (!$this->requireRole('admin')) {
            $this->redirect('home');
        }
        return $this->render('adminSite.view',
            [
                'heading' => 'admin',
                'adminName' => $this->session->get('userName'),
                'categoryCount' => count($this->categoriesData),
                'blogCount' => count($this->blogData),
                'userCount' => count($this->usersData)
            ]
        );
    }

    public function showUsers(): void
    {

        $this->jsonResponse([
            'success' => !empty($this->usersData) ?? false,
            'users' => $this->usersData ?? null]);
    }

    public function updateUser(): void
    {
        if (!$this->isPost()) {
            $this->redirect('admin');
        }
            $this->jsonResponse([
                'success' => $this->adminSiteModel->updateUserData($this->post(), $this->message),
                'message' => $this->message,
            ]);
    }

    public function deleteUser(): void
    {
        $this->jsonResponse([
            'success' => $this->adminSiteModel->deleteUser($this->post('id'), $this->message),
            'message' => $this->message,
        ]);
    }

    public function showCategories(): void
    {
        $this->jsonResponse([
            'success' => !empty($this->categoriesData) ?? false,
            'categories' => $this->categoriesData,
        ]);
    }

    public function updateCategory(): void
    {
        if (!$this->isPost()) {
            $this->redirect('admin');
        }
                $this->jsonResponse([
                    'success' => $this->adminSiteModel->updateCategory($this->post(), $this->message),
                    'message' => $this->message
                ]);
    }

    public function deleteCategory(): void
    {
        $this->jsonResponse([
            'success' => $this->adminSiteModel->deleteCategory($this->post('cate_id'), $this->message),
            'message' => $this->message,
        ]);
    }

    public function showBlog(): void
    {
        $this->jsonResponse([
            'success' => !empty($this->blogData) ?? false,
            'blogs' => $this->blogData,
        ]);
    }

    public function updateBlog(): void
    {
        if (!$this->isPost()) {
            $this->redirect('admin');
        }
                $this->jsonResponse([
                    'success' => $this->adminSiteModel->updateBlog($this->post(), $this->message),
                    'message' => $this->message,
                ]);
    }

    public function deleteBlog(): void
    {
        $this->jsonResponse([
            'success' => $this->adminSiteModel->deleteBlog($this->post('blog_id'), $this->message),
            'message' => $this->message,
        ]);
    }
}