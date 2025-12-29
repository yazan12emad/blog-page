<?php

namespace app\models;

use app\core\DataBase;
use app\core\Model;
use PDOException;

class categoriesModel extends Model
{

    private DataBase $DataBase;

    private ValidationClass $validationClass;
    public function __construct(){
    $this->DataBase = DataBase::getInstance();
    $this->validationClass = new ValidationClass();

    }

    public function getCategories(&$message = null): array {
        try {
            return $this->DataBase->query("SELECT * FROM categories")->fetchAll();
        }
        catch (PDOException $e) {
            $message = 'Error while updating user ';
            return [];
        }
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

    public function addCategory($cate_name ,$admin_id ,$der , &$message):bool {
        if(!$this->validationClass->validateCategoryData($cate_name ,$der ,$message  )){
            return false;
        }
        if($this->checkIfExist($cate_name))
        {
            $message = 'category already exists';
            return false;
        }

        if($this->DataBase->query("INSERT INTO categories (cate_name, admin_id ,description ) VALUES (:cate_name,:admin_id , :description)" ,
            [
                'cate_name' => $cate_name,
                'admin_id' => $admin_id,
                'description' => $der
            ]))
         {
             $message = 'new category added';
             return true;
         }

             $message = 'error to add new category';
             return false;

    }

    public function updateCategory($cate_id ,$new_name , &$message , $description): bool
    {
        try {
            if(!$this->validationClass->validateCategoryData($new_name ,$description ,$message  )){
                return false;
            }

                if ($this->DataBase->query("UPDATE categories set cate_name = :new_name  , description = :description WHERE cate_id = :cate_id", [
                    'new_name' => trim($new_name),
                    'cate_id' => $cate_id,
                    'description' => trim($description)
                ]))
                {
                    $message = 'category updated';
                    return true;
                }
                    $message = 'error to update category';
                    return false;


        }
        catch (\Exception $e) {
            $message = 'Error while updating category';
            return false;
        }

    }

    public function deleteCategory($cate_id , &$message): bool {
        try {


            if ($this->DataBase->query("DELETE FROM categories WHERE cate_id = :cate_id",
                [
                    'cate_id' => $cate_id
                ])) {
                $message = 'category deleted';
                return true;
            } else {
                $message = 'error to delete category';
                return false;
            }
        }
        catch (\Exception $e) {
            $message = 'Error while deleting category';
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