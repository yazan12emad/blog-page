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
    private array  $blogData;



    public  function __construct(){
        $this->adminSiteModel = new AdminSiteModel();
        $this->session = Session::getInstance();

        $this->usersData = $this->adminSiteModel->getUsers();
        $this->categoriesData = $this->adminSiteModel->getCategories();
        $this->blogData = $this->adminSiteModel->getBlogs();

    }

    public  function showSite(array $extra = [])
    {
        if($this->session->userRole() == 'admin')
        return $this->render('adminSite.view' ,
            array_merge([
                'heading' => 'admin',
                'adminName'=> $this->session->get('userName'),
                'categoryCount' =>count($this->categoriesData),
                'blogCount' =>count($this->blogData),
                    'userCount'=> count($this->usersData)
                ]
         , $extra));
        else {
            $this->redirect('home');
        return false;
        }
    }
    public function showUsers():void
    {

        Header('content-type: application/json');
        echo JSON_encode([
            'success' => true ,
                'users' => $this->usersData ,
            ]

        );
        exit;

    }

    public function editUser():void
    {
        if($this->isPost()) {


            if ($_POST['action'] === 'showToEdit') {
                Header('content-type: application/json');

                echo JSON_encode([
                    'success' => true,
                    'userData' => $this->adminSiteModel->getUserById($_POST['id']),
                ]);
                exit;

            }

            if ($_POST['action'] === 'updateUser') {
                Header('content-type: application/json');
                echo JSON_encode([
                    'success' => $this->adminSiteModel->updateUserData($_POST, $msg),
                    'message' => $msg,
                    'post' => $_POST,

                ]);
                exit;

            }
        }
        else
            $this->redirect('home');

    }

    public  function deleteUser(): void
    {
        Header('content-type: application/json');
        echo JSON_encode([
            'success' => $this->adminSiteModel->deleteUser($_POST['id'] , $msg) ,
            'message'=>$msg,
        ]);
        exit;
    }

    public  function showCategories():void
    {
        Header('content-type: application/json');
        echo JSON_encode([
            'success' => true ,
            'categories' => $this->categoriesData ,
        ]);
        exit;

    }


public function editCategory():void{
    if ($_POST['action'] === 'showCategory') {
        Header('content-type: application/json');

        echo JSON_encode([
            'success' => true,
            'category' => $this->adminSiteModel->getCategoryById($_POST['cate_id']),
        ]);
        exit;

    }

    if ($_POST['action'] === 'updateCategory') {
        Header('content-type: application/json');
        echo JSON_encode([
            'success' => $this->adminSiteModel->updateCategory($_POST , $msg),
            'message' => $msg
        ]);

        exit;

    }


}

    public function deleteCategory(): void{
        header('content-type: application/json');
        echo JSON_encode([
            'success' => $this->adminSiteModel->deleteCategory($_POST['cate_id'] , $msg) ,
            'message' => $msg,
        ]);
        exit;


    }
        public  function showBlog():void
        {
            Header('content-type: application/json');
            echo JSON_encode([
                'success' => true ,
                'blogs' => $this->blogData ,
            ]);
            exit;


        }

        public function editBlog():void{

            if($_POST['action'] == 'showBlog'){

                Header('content-type: application/json');
                echo JSON_encode([
                    'success' => true,
                    'blog' =>$this->adminSiteModel->getBlogById($_POST['blog_id']),
                ]);
                exit;

            }

            if($_POST['action'] == 'updateBlog'){
                header('content-type: application/json');
                echo JSON_encode([
                    'success' =>$this->adminSiteModel->updateBlog($_POST , $msg) ,
                    'message' => $msg ,
                ]);
                exit;

            }




        }

        public function deleteBlog():void{
            Header('content-type: application/json');
            echo JSON_encode([
                'success' => $this->adminSiteModel->deleteBlog($_POST['blog_id'] , $msg) ,
                'message' => $msg ,
            ]);
            exit;


        }






}