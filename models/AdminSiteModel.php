<?php

namespace app\models;

use app\core\DataBase;

class AdminSiteModel
{
    private DataBase $db;

    private UserModel $UserModel;

    private BlogModel $BlogModel;

    private CategoriesModel $CategoriesModel;

    private ValidationClass $ValidationClass;


    public function __construct()
    {
        $this->db = DataBase::getInstance();

        // data objects
        $this->UserModel = new UserModel();
        $this->BlogModel = new BlogModel();
        $this->CategoriesModel = new CategoriesModel();
        $this->ValidationClass = new ValidationClass();

    }

    public function getUsers(): array
    {
        return $this->UserModel->getAllUsersData();
    }

    public function getUserById($id): array
    {
        try {
            return $this->db->query('SELECT id ,userName , emailAddress , user_role FROM UsersInformation WHERE id = :id ',
                [
                    'id' => $id
                ]
            )->fetch(\PDO::FETCH_ASSOC);
        }
        catch (\PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    public function deleteUser($userId, &$msg): bool
    {
        if ($this->UserModel->deleteUser($userId)) {
            $msg = 'delete user';
            return true;
        } else {
            $msg = 'error while delete ';
            return false;

        }
    }


    public function updateUserData($userNewData, &$msg, $update = 0)
    {
        $oldData = $this->getUserById($userNewData['id']);

        if ($userNewData['userName'] !== $oldData['userName']) {

            if ($this->updateUserName($userNewData['id'], $userNewData['userName'], $oldData['userName'], $msg))
                $update++;

        }
        if ($userNewData['emailAddress'] !== $oldData['emailAddress']) {
            if ($this->updateUserEmailAddress($userNewData['id'], $userNewData['emailAddress'], $oldData['emailAddress'], $msg))
                $update++;
        }

        if ($userNewData['user_role'] !== $oldData['user_role'])
            if ($this->updateUserRole($userNewData['id'], $userNewData['user_role'], $msg))
                $update++;

        return $update;

    }


    public function updateUserName($id, $newName, $oldName, &$msg = []): bool
    {
        if ($this->UserModel->changeProfileUserName($id, $newName, $oldName, $msg))
            return true;
        else {
            return false;
        }

    }

    public function updateUserEmailAddress($id, $newName, $oldName, &$msg = []): bool
    {
        if ($this->UserModel->changeProfileEmail($id, $newName, $oldName, $msg))
            return true;
        else {
            return false;
        }
    }

    public function updateUserRole($id, $newName, &$msg): bool
    {
        if ($this->UserModel->updateUserData($id, 'user_role', $newName))
            return true;
        else {
            $msg = ' error in change user role ';
            return false;
        }
    }

    public function getCategories(): array
    {
        return $this->CategoriesModel->getCategories();
    }

    public function getCategoryById($id): array{
        return $this->CategoriesModel->getCategoryById($id);
    }

    public function updateCategory($cate_data , &$msg):bool{


        if ($this->CategoriesModel->updateCategory($cate_data['cate_id'], $cate_data['cate_name'], $msg , $cate_data['description']))
            return true;
        else {
            return false;
        }
    }

    public function deleteCategory($cate_id, &$msg): bool
    {
        if ($this->CategoriesModel->deleteCategory($cate_id, $msg))
            return true;
        else {
            $msg = 'error while delete category ';
            return false;
        }

    }

    public function getBlogs(): array
    {
        return $this->BlogModel->getAllBlogs();
    }

    public function getBlogById($id): array{
        return $this->BlogModel->getBlogById($id);

    }

    public function updateBlog($BlogNewData , &$msg)
    {
        $blogCurrentData = $this->BlogModel->getBlogById($BlogNewData['blog_id']);
        $update = 0;

        if (trim($BlogNewData['blog_title']) === $blogCurrentData['blog_title'])
        {
            $msg = 'The title is the same old title';
        }

        else if(!$this->ValidationClass->notEmpty(trim($BlogNewData['blog_title'])))
        {
            $msg = 'The title required';
        }
        else {
            if($this->BlogModel->updateBlogs($BlogNewData['blog_id'],'blog_title', trim($BlogNewData['blog_title']) ,$msg))
            $update++;
        }


        if (trim($BlogNewData['blog_body']) === $blogCurrentData['blog_body']){
            $msg = 'The body is the same old body';
        }
        else if (!$this->ValidationClass->notEmpty(trim($BlogNewData['blog_body'])))
            $msg = 'The body required';
        else {
            if($this->BlogModel->updateBlogs($BlogNewData['blog_id'],'blog_body', trim($BlogNewData['blog_body']) ,$msg))
            $update++;
        }

        if($BlogNewData['blog_status'] === $blogCurrentData['blog_status'])
            return $update;
        else if (!$this->ValidationClass->notEmpty($BlogNewData['blog_status']))
            $msg = 'The status required';
        else
            if($this->BlogModel->updateBlogs($BlogNewData['blog_id'],'blog_status', $BlogNewData['blog_status'] ,$msg))
                return ++$update;
            return $update;



    }

    public function deleteBlog($blog_id, &$msg): bool
    {
        if ($this->BlogModel->deleteBlogs($blog_id, $msg))
            return true;
        else {
            $msg = 'error while delete category ';
            return false;
        }

    }



}