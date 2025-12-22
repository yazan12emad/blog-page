<?php

namespace app\models;

use app\core\DataBase;
use app\core\Model;
use app\core\Session;

class categoriesModel extends Model
{
    private Session $session;

    private DataBase $DataBase;

    public function __construct(){
    $this->session =  Session::getInstance();
    $this->DataBase = DataBase::getInstance();
    }

    public function getCategories(): array {
        return $this->DataBase->query("SELECT * FROM categories")->fetchAll();
    }


    public function getCategoriesName($id): array {
        return $this->DataBase->query("SELECT cate_name FROM categories where cate_id = :id",[
            "id" => $id
        ])->fetchAll();
    }

    public function checkIfExist($new_name): bool
    {
        $allCategory = $this->getCategories();
        foreach ($allCategory as $category) {
            if (trim($category['cate_name']) === trim($new_name)) {
                return true;
            }
        }
        return false;
    }

    public function addCategory($cate_name ,$admin_id ,$der , &$msg):bool {
        if($this->checkIfExist($cate_name))
        {
            $msg = 'category already exists';
            return false;
        }

        else  if($this->DataBase->query("INSERT INTO categories (cate_name, admin_id ,description ) VALUES (:cate_name,:admin_id , :description)" ,
            [
                'cate_name' => $cate_name,
                'admin_id' => $admin_id,
                'description' => $der
            ]))
         {
             $msg = 'new category added';
             return true;
         }
         else {
             $msg = 'error to add new category';
             return false;
         }
    }

    public function updateCategory($cate_id ,$new_name , &$msg , $der): bool
    {
        try {
            if (empty(trim($new_name))) {
                $msg = 'new category name is required';
                return false;
            }
            if (empty(trim($der))) {
                $msg = 'new category description is required';
                return false;
            }

                if ($this->DataBase->query("UPDATE categories set cate_name = :new_name  , description = :description WHERE cate_id = :cate_id", [
                    'new_name' => trim($new_name),
                    'cate_id' => $cate_id,
                    'description' => trim($der)
                ]))
                {
                    $msg = 'category updated';
                    return true;
                }


                    $msg = 'error to update category';
                    return false;


        }
        catch (\Exception $e) {
            echo $e->getMessage();
            $msg = $e->getMessage();
            return false;
        }

    }

    public function deleteCategory($cate_id , &$msg): bool {
         if($this->DataBase->query("DELETE FROM categories WHERE cate_id = :cate_id" ,
            [
                'cate_id' => $cate_id
            ]))
         {
             $msg = 'category deleted';
             return true;
         }
         else {
             $msg = 'error to delete category';
             return false;
         }
    }

    public function getCategoryById($cate_id): array {
        return $this->DataBase->query("SELECT * FROM categories WHERE cate_id = :cate_id" ,
            [
                'cate_id' => $cate_id
            ])->fetch(\PDO::FETCH_ASSOC);
    }






}