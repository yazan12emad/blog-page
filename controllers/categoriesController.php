<?php

// simplify done

namespace app\controllers;

use app\core\Controller;
use app\models\categoriesModel;
use app\core\Session;

class categoriesController extends Controller
{
    private $categoriesModel;

    private $session;

    private $msg = [];


    public function __construct(){
        $this->categoriesModel = new categoriesModel();
        $this->session = Session::getInstance();
    }

    public function showCategories(): string
    {
        $results = $this->categoriesModel->getCategories();
        return  $this->render('categories.view' , [
            'heading' => 'Categories' ,
            'results' => !empty($results) ? $results: 'these is no categories',
        ]);
    }

    public function addCategory(){
        if(!$this->isPost()) {
            $this->redirect('categories');
        }

        $new_Category = $this->post('cate_name');
        $der =$this->post('cate_desc');
        $admin_id = $this->session->get('id');

        $this->jsonResponse([
            'success'=>$this->categoriesModel->addCategory($new_Category ,$admin_id , $der,$this->msg),
            'message'=>$this->msg,
        ]);
    }

    public function editCategory(){
        if(!$this->isPost()) {
            $this->redirect('categories');

        }
        $cate_id = $this->post('cate_id');
        $new_Category = $this->post('cate_name');
        $der =$this->post('cate_desc');

        $this->jsonResponse([
            'success' => $this->categoriesModel->updateCategory($cate_id , $new_Category, $this->msg , $der),
            'message' =>$this->msg,
            'id'=> $cate_id
        ]);
    }

    public function deleteCategory()
    {
        $cate_id = $this->post('cate_id');
        $this->jsonResponse([
            'success' => $this->categoriesModel->deleteCategory($cate_id , $this->msg),
            'message' =>'category deleted',
        ]);
    }
}



