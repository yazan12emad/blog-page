<?php

use app\controllers\AuthController;
use app\controllers\BlogController;
use app\controllers\categoriesController;
use app\controllers\AdminSite;
use app\controllers\FullViewController;
use app\controllers\HomeController;


return [

    // AuthController class
    '/login' => [AuthController::class, 'login'],
    '/signUp' => [AuthController::class, 'register'],
    '/home' => [HomeController::class, 'showHome'],
    '/profile' => [AuthController::class, 'profile'],
    '/logout' => [AuthController::class, 'logout'],
    '/forgetPassword' => [AuthController::class, 'forgetPassword'],
    '/submitNewPassword' => [AuthController::class, 'submitNewPassword'],

    // blog class

    '/blog/([\w\d\-]+)' => [BlogController::class, 'showBlogs'],
    '/blog/filter' => [BlogController::class, 'showBlogs'],

    '/blog' => [BlogController::class, 'showBlogs'],
    '/blog/create' => [BlogController::class, 'createNewBlog'],
    '/blog/update' => [BlogController::class, 'updateBlog'],
    '/blog/delete' => [BlogController::class, 'deleteBlog'],

    // categories class
    '/categories' => [categoriesController::class, 'showCategories'],
    '/categories/add' => [categoriesController::class, 'addCategory'],
    '/categories/edit' => [categoriesController::class, 'editCategory'],
    '/categories/delete' => [categoriesController::class, 'deleteCategory'],


    //admin / user
    '/admin' => [AdminSite::class, 'showSite'],

    '/admin/users' => [AdminSite::class, 'showUsers'],
    '/admin/users/delete' => [AdminSite::class, 'deleteUser'],
    '/admin/users/update' => [AdminSite::class, 'updateUser'],

    //admin / category
    '/admin/category' => [AdminSite::class, 'showCategories'],
    '/admin/categories/delete' => [AdminSite::class, 'deleteCategory'],
    '/admin/categories/update' => [AdminSite::class, 'updateCategory'],

//admin / blog
    '/admin/blog' => [AdminSite::class, 'showBlog'],
    '/admin/blog/delete' => [AdminSite::class, 'deleteBlog'],
    '/admin/blog/update' => [AdminSite::class, 'updateBlog'],

    '/Full-Blog/like' => [FullViewController::class, 'addLike'],
    '/Full-Blog/comment' => [FullViewController::class, 'addComment'],
    '/Full-Blog/reply' => [FullViewController::class, 'addComment'],
    '/Full-Blog\/([\w\-\.\,\d]+)\/([\d]+)' => [FullViewController::class, 'showBlog'],


];

