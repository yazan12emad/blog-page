<?php

namespace app\models;


class AdminSiteModel
{

    private userModel $userModel;

    private blogModel $blogModel;

    private categoriesModel $categoriesModel;

    private validationClass $validationClass;


    public function __construct()
    {

        // data objects
        $this->userModel = new userModel();
        $this->blogModel = new blogModel();
        $this->categoriesModel = new categoriesModel();
        $this->validationClass = new validationClass();

    }

    public function getUsers(): array
    {
        return $this->userModel->getAllUsersData();
    }

    public function deleteUser($userId, &$message): bool
    {
        if ($this->userModel->deleteUser($userId, $message)) {
            $message = 'delete user';
            return true;
        }
        $message = 'error while delete';
        return false;


    }

    public function updateUserData($userNewData, &$message, $update = 0)
    {
        $oldData = $this->userModel->getUserDataById($userNewData['id']);

        if (empty($oldData)) {
            $message = 'Error in update user data';
            return false;
        }

        if ($userNewData['userName'] !== $oldData['userName']) {

            if ($this->updateUserName($userNewData['id'], $userNewData['userName'], $oldData['userName'], $message))
                $update++;
        }

        if ($userNewData['emailAddress'] !== $oldData['emailAddress']) {
            if ($this->updateUserEmailAddress($userNewData['id'], $userNewData['emailAddress'], $oldData['emailAddress'], $message))
                $update++;
        }

        if ($userNewData['user_role'] !== $oldData['user_role'])
            if ($this->updateUserRole($userNewData['id'], $userNewData['user_role'], $message))
                $update++;
        return $update;
    }


    public function updateUserName($id, $newName, $oldName, &$message): bool
    {
        return $this->userModel->changeProfileUserName($id, $newName, $oldName, $message) ?? false;


    }

    public function updateUserEmailAddress($id, $newName, $oldName, &$message = []): bool
    {

        return $this->userModel->changeProfileEmail($id, $newName, $oldName, $message) ?? false;


    }

    public function updateUserRole($id, $newName, &$message): bool
    {
        return $this->userModel->updateUserData($id, 'user_role', $newName, $message) ?? false;

    }

    public function getCategories(): array
    {
        return $this->categoriesModel->getCategories();
    }


    public function updateCategory($cate_data, &$message)
    {
        return $this->categoriesModel->updateCategory($cate_data['cate_id'], $cate_data['cate_name'], $message, $cate_data['description']) ?? false;
    }

    public function deleteCategory($cate_id, &$message): bool
    {
        return $this->categoriesModel->deleteCategory($cate_id, $message) ?? false;

    }

    public function getBlogs(): array
    {
        return $this->blogModel->getAllBlogs();
    }


    public function updateBlog($BlogNewData, &$message)
    {
        $blogCurrentData = $this->blogModel->getBlogById($BlogNewData['blog_id']);
        $update = 0;

//        if($this->blogModel->validateUpdateBlogData($blogCurrentData , $message))
//            $update++;

        if (trim($BlogNewData['blog_title']) === $blogCurrentData['blog_title']) {
            $message = 'The title is the same old title';
        }

        if (!$this->validationClass->notEmpty(trim($BlogNewData['blog_title']))) {
            $message = 'The title required';
        }

        if ($this->blogModel->updateBlogs($BlogNewData['blog_id'], 'blog_title', trim($BlogNewData['blog_title']), $message))
            $update++;


        if (trim($BlogNewData['blog_body']) === $blogCurrentData['blog_body']) {
            $message = 'The body is the same old body';
        }
        if (!$this->validationClass->notEmpty(trim($BlogNewData['blog_body'])))
            $message = 'The body required';

        if ($this->blogModel->updateBlogs($BlogNewData['blog_id'], 'blog_body', trim($BlogNewData['blog_body']), $message))
            $update++;


        if ($BlogNewData['blog_status'] === $blogCurrentData['blog_status'])
            return $update;

        if (!$this->validationClass->notEmpty($BlogNewData['blog_status']))
            $message = 'The status required';

        if ($this->blogModel->updateBlogs($BlogNewData['blog_id'], 'blog_status', $BlogNewData['blog_status'], $message))

            return ++$update;
        return $update;

    }

    public function deleteBlog($blog_id, &$message): bool
    {
        return $this->blogModel->deleteBlogs($blog_id, $message) ?? false;
    }
}