<?php

// this page for log In calling function for log in process
/*example.com/posts/view/1


=> controller => posts
=> action => view
=> params => 1
=> model  => Post Model => get post 1
=> view => post => render

Posts => Create , Read, Update , Delete
CRUD => Create, Read , Update , Delete*/

use app\core\DataBase;
use app\core\session;
use app\models\ValidationClass;

if (isset($_SESSION['id'])) {
    header('Location: ../');
    die('Direct access is not permitted.');
}

$session = new Session();
$VC = new ValidationClass();
$db = new DataBase();

$heading = "log In ";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    $input = trim($_POST['userName']);
    $password = trim($_POST['password']);

    if (!$VC->notEmpty($input)) {
        $errors['userName'] = "Username or email is required";
    } else if (!$VC->validationPassword($password)) {
        $errors['password'] = "Password must be at least 8 characters long";
    } else {
        // Determine input type
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $user = $db->getUserEmail($input);
        } else {
            $user = $db->getUserInfo($input);
        }

        // Check password
        if ($user && password_verify($password, $user['password']))
        {
            $session->setSessionData($user);
            header('location: /home.php');
            exit;
        }
        else if($user['password'] === $password)
        {
            $session->setSessionData($user);
            header('location: /home.php');
            exit;
        } else {
            $errors['login'] = "Invalid username/email or password";
        }
    }
}



require "views/logIn.view.php";



#####
//class Login{
//    public function index()
//    {
//        // validation
//        ob_start();
//        require "views/logIn.view.php";
//        ob_end_clean();
//        return ;
//    }
//}