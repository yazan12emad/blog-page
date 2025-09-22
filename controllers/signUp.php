<?php

// this page for sign in calling function for sign in process

if (isset($_SESSION['id'])) {
    header('Location: ../');
    die('Direct access is not permitted.');
}

use app\core\session;
use app\core\DataBase;
use app\models\ValidationClass;

$session = new session();
$VC = new ValidationClass();
$db = new DataBase();

$heading = "Sign Up";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = [];

    if (!($VC->notEmpty($_POST['userName']))) {
        $error['userName'] = "Username is required";
    }

    $chickUserName = $db->getUserInfo($_POST['userName']);

    if ($chickUserName && $chickUserName['userName'] === trim($_POST['userName'])) {
        $error['userName'] = "Username is already taken";
    }

    if (!($VC->validateEmail($_POST['emailAddress']))) {
        $error['emailAddress'] = "Email Address is required";
    }

    $chickUserEmail = $db->getUserEmail($_POST['emailAddress']);
    if ($chickUserEmail && $chickUserEmail['emailAddress'] === trim($_POST['emailAddress'])) {
        $error['emailAddress'] = "Email is already taken";
    }

    if (!($VC->validationPassword($_POST['password']))) {
        $error['password'] = "Password must be at least 8 characters long";
    }

    if (empty($error)) {
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $db->addNewUser($_POST['userName'], $_POST['emailAddress'], $hashedPassword);

        $newUser = $db->getUserInfo($_POST['userName']);
        $session->setSessionData($newUser);


        header('location: /home.php');
        return;
    }
}


require "views/signUp.view.php";
