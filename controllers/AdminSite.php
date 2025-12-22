<?php

// simplify done

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
        $this->jsonResponse(['success' => true, 'users' => $this->usersData]);

    }

    public function editUser(): void
    {
        if (!$this->isPost()) {
            $this->redirect('admin');
        }

        if ($this->post('action') === 'showToEdit') {

            Header('content-type: application/json');
            $this->jsonResponse([
                'success' => true,
                'userData' => $this->adminSiteModel->getUserById($this->post('id'))]);

        }

        if ($this->post('action') === 'updateUser') {

            $this->jsonResponse(['success' => $this->adminSiteModel->updateUserData($this->post(), $msg),
                'message' => $msg,
                'post' => $this->post(),
            ]);


        }


    }

    public function deleteUser(): void
    {
        $this->jsonResponse(['success' => $this->adminSiteModel->deleteUser($this->post('id'), $msg),
            'message' => $msg,
        ]);

    }

    public function showCategories(): void
    {
        $this->jsonResponse([
            'success' => true,
            'categories' => $this->categoriesData,
        ]);


    }


    public function editCategory(): void
    {
        if (!$this->isPost()) {
            $this->redirect('admin');
        }

        $action = $this->post('action');

        if (!$action) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'There is no action',]);
        }

        switch ($action) {
            case 'showCategory':
                $this->jsonResponse([
                    'success' => true,
                    'category' => $this->adminSiteModel->getCategoryById($this->post('cate_id'))
                ]);
                break;

            case 'updateCategory':
                $this->jsonResponse([
                    'success' => $this->adminSiteModel->updateCategory($this->post(), $msg),
                    'message' => $msg
                ]);
                break;

            default:
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'There was a problem with the action',
                ]);

        }

    }

    public function deleteCategory(): void
    {
        $this->jsonResponse([
            'success' => $this->adminSiteModel->deleteCategory($this->post('cate_id'), $msg),
            'message' => $msg,
        ]);

    }

    public function showBlog(): void
    {
        $this->jsonResponse([
            'success' => true,
            'blogs' => $this->blogData,
        ]);

    }

    public function editBlog(): void
    {
        if (!$this->isPost()) {
            $this->redirect('admin');
        }

        $action = $this->post('action');

        if (!$action) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'There is no action',
            ]);
        }

        switch ($action) {
            case 'showBlog':
                $this->jsonResponse([
                    'success' => true,
                    'blog' => $this->adminSiteModel->getBlogById($this->post('blog_id')),
                ]);
                break;

            case 'updateBlog':
                $this->jsonResponse([
                    'success' => $this->adminSiteModel->updateBlog($this->post(), $msg),
                    'message' => $msg,
                ]);
                break;
            default:
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'There was a problem with the action',
                ]);
        }


    }

    public function deleteBlog(): void
    {
        $this->jsonResponse([
            'success' => $this->adminSiteModel->deleteBlog($this->post('blog_id'), $msg),
            'message' => $msg,
        ]);


    }


}