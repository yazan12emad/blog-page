<?php
//
//// this page for sign in calling function for sign in process
//
//if (isset($_SESSION['id'])) {
//    header('Location: ../');
//    die('Direct access is not permitted.');
//}
//
//use app\core\session;
//use app\models\ValidationClass;
//use app\models\UserModel;
//
//$session = new session();
//$VC = new ValidationClass();
//$UM = new UserModel();
//
//
//$heading = "Sign Up";
//
//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//    $error = [];
//
//    if (!($VC->notEmpty($_POST['userName']))) {
//        $error['userName'] = "Username is required";
//    }
//
//
//    $chickUserName = $UM->getUserInfo($_POST['userName']);
//
//    if ($chickUserName && $chickUserName['userName'] === trim($_POST['userName'])) {
//        $error['userName'] = "Username is already taken";
//    }
//
//    if (!($VC->validateEmail($_POST['emailAddress']))) {
//        $error['emailAddress'] = "Email Address is required";
//    }
//
//    $chickUserEmail = $UM->getUserEmail($_POST['emailAddress']);
//    if ($chickUserEmail && $chickUserEmail['emailAddress'] === trim($_POST['emailAddress'])) {
//        $error['emailAddress'] = "Email is already taken";
//    }
//
//    if (!($VC->validationPassword($_POST['password']))) {
//        $error['password'] = "Password must be at least 8 characters long";
//    }
//
//    if (empty($error)) {
//        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
//
//        $UM->addNewUser($_POST['userName'], $_POST['emailAddress'], $hashedPassword);
//
//        $newUser = $UM->getUserInfo($_POST['userName']);
//        $session->setSessionData($newUser);
//
//
//        header('location: /home.php');
//        return;
//    }
//}
//
//
//require "views/signUp.view.php";
