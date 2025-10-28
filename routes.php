<?php

use app\controllers\AuthController;
use app\controllers\BlogController;
use app\controllers\categoriesController;



return  [

        // AuthController class
        '/login' => [AuthController::class, 'login'],
        '/signUp' => [AuthController::class, 'register'],
        '/home' => [AuthController::class, 'showHome'],
        '/profile' => [AuthController::class, 'profile'],
        '/logout' => [AuthController::class, 'logout'],
        '/forgetPassword' => [AuthController::class, 'forgetPassword'],
        '/submitNewPassword' =>[AuthController::class, 'submitNewPassword'],

    // blog class

    '/blog' => [BlogController::class, 'showBlogs'],
    '/blog/create'=>[BlogController::class, 'createNewBlog'],

    // categories class
    '/categories' => [categoriesController::class , 'showCategories'],
    '/categories/add' => [categoriesController::class , 'addCategory'],
    '/categories/edit' => [categoriesController::class , 'editCategory'],
    '/categories/delete' => [categoriesController::class , 'deleteCategory'],





];

