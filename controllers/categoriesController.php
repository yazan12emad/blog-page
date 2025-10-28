<?php

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
        return  $this->render('categories.view' , ['heading' => 'Categories' , 'results' => $results]);

    }

    public function addCategory(){
        if($this->isPost())
        {
           $new_Category = $_POST['cate_name'];
           $admin_id = $this->session->get('id');
            header('Content-Type: application/json');
            echo json_encode(
                [
                    'success'=>$this->categoriesModel->addCategory($new_Category ,$admin_id ,$this->msg),
                    'message'=>$this->msg

                ]);
    exit();
        }
    }

    public function editCategory(){
        if($this->isPost()) {
            $cate_id = $_POST['cate_id'];
            $new_Category = $_POST['cate_name'];

            echo json_encode(
                [
                    'success' => $this->categoriesModel->updateCategory($cate_id , $new_Category, $this->msg)
                    ,'message' =>$this->msg,
                'id'=> $cate_id]
            );
        }
        exit();
    }

    public function deleteCategory()
    {
        $cate_id = $_POST['cate_id'];
        echo json_encode(
            [
                'success' => $this->categoriesModel->deleteCategory($cate_id , $this->msg)
                ,'message' =>'category deleted'

        ]);


    }




}

// call the db function and function to show the blogs  related to specific category


