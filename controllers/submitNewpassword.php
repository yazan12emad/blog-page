<?php


use app\core\DataBase;
use app\core\session;
use app\models\ValidationClass;


$VC = new ValidationClass();
$db = new DataBase();
$session = new Session();

$heading = 'reset password ';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $currentID = $session->get('id');
    $inputToken = trim($_POST['token']);
    $newPassword = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $savedToken = $db->getToken($currentID);


    if ($savedToken) {
        if ($savedToken['token'] !== $inputToken) {
            $error['tokenError'] = 'The code is not correct';
        } else if (!$VC->validationPassword($newPassword)) {
            $error['newPassword'] = 'The password is invalid';
        } else if (!$VC->validationPassword($confirmPassword)) {
            $error['confirmPassword'] = 'The password is invalid';
        } else if ($newPassword !== $confirmPassword) {
            $error['confirmPassword'] = 'The new password does not match';
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            if ($db->updateUserData($currentID, 'password', $hashedPassword)) {
                $db->markTokenAsUsed($currentID, $savedToken['token']);

                $success['message'] = 'Password changed successfully';

                header('Location: home.php');
                exit;
            } else {
                $error['confirmPassword'] = 'Error happened, try later';
            }
        }
    }

}


require 'views/submitNewPassword.view.php';