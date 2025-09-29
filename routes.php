<?php

use app\controllers\AuthController;
if (!defined('SECURE_BOOT')) {
    header('Location: ../');
    die('Direct access is not permitted.');
}


return  [

        "/blogs.php" => __DIR__ . "/../controllers/blogs.php",
        "/categories.php" => __DIR__ . "/../controllers/categories.php",

        '/logIn.php' => [AuthController::class, 'login'],
        '/signUp.php' => [AuthController::class, 'register'],
        '/home.php' => [AuthController::class, 'showHome'],
        '/profile.php' => [AuthController::class, 'profile'],
        '/logout.php' => [AuthController::class, 'logout'],
        '/forgetPassword.php' => [AuthController::class, 'forgetPassword'],
        '/submitNewPassword.php' =>[AuthController::class, 'submitNewPassword'],




];

