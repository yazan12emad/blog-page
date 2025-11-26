<?php

use app\controllers\AuthController;
use app\controllers\BlogController;
use app\controllers\categoriesController;
use app\controllers\AdminSite;
use app\controllers\FullViewController;
use app\controllers\HomeController;



return  [

        // AuthController class
        '/login' => [AuthController::class, 'login'],
        '/signUp' => [AuthController::class, 'register'],
        '/home' => [HomeController::class, 'showHome'],
        '/profile' => [AuthController::class, 'profile'],
        '/logout' => [AuthController::class, 'logout'],
        '/forgetPassword' => [AuthController::class, 'forgetPassword'],
        '/submitNewPassword' =>[AuthController::class, 'submitNewPassword'],

    // blog class

    '/blog/([\w\-]+)' => [BlogController::class, 'showBlogs'],
    '/blog' => [BlogController::class, 'showBlogs'],
    '/blog/create'=> [BlogController::class, 'createNewBlog'],
    '/blog/delete'=> [BlogController::class, 'deleteBlog'],

    // categories class
    '/categories' => [categoriesController::class , 'showCategories'],
    '/categories/add' => [categoriesController::class , 'addCategory'],
    '/categories/edit' => [categoriesController::class , 'editCategory'],
    '/categories/delete' => [categoriesController::class , 'deleteCategory'],


        //admin / user
    '/admin' => [AdminSite::class , 'showSite'],
    '/admin/users' => [AdminSite::class , 'showUsers'],
    '/admin/users/delete'=> [AdminSite::class , 'deleteUser'],
    '/admin/users/edit'=> [AdminSite::class , 'editUser'],
    '/admin/users/update' => [AdminSite::class , 'editUser'],

    //admin / category
    '/admin/category' => [AdminSite::class , 'showCategories'],
    '/admin/categories/delete' => [AdminSite::class , 'deleteCategory'],
    '/admin/categories/edit' => [AdminSite::class , 'editCategory'],
    '/admin/categories/update'=> [AdminSite::class , 'editCategory'],

//admin / blog
    '/admin/blog' => [AdminSite::class , 'showBlog'],
    '/admin/blog/delete' => [AdminSite::class , 'deleteBlog'],
    '/admin/blog/edit'=> [AdminSite::class , 'editBlog'],
    '/admin/blog/update/'=> [AdminSite::class , 'editBlog'],


    '/Full-Blog/([\w\-]+)/([\d]+)'=> [FullViewController::class , 'showBlog'],


];

