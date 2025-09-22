<?php

use app\core\session;
use app\core\DataBase;
use app\models\ValidationClass;
use app\models\UploadFiles;

$session = new Session();
$db = new DataBase();
$VD = new ValidationClass();
$UF = new UploadFiles();

$heading = "profile ";


$currentID = trim($session->get("id"));
$currentUserName = trim($session->get("userName"));
$currentUserEmail = trim($session->get("emailAddress"));
$imgBefore = $db->getUserImg($currentID)['profileImg'];
$session->set("imgBefore", $imgBefore);
$currentUserPassword = trim($session->get("password"));
$oldPassword = $db->getUserInfo($currentUserName)['password'];

//$data=$db->getUserInfo($currentUserName)['password'];


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $error = [];

    // Img change  ((((((done))))))
    if (isset($_FILES['profileImage'])) {
        $msg = "";
        $uploadOk = 0;
        $newImg = $UF->addImg($_FILES['profileImage'], $uploadOk, $msg);

        if ($newImg !== false) {
//            $UF->deletePastImg(($imgBefore));
            $imgBefore = $db->getUserImg($currentID)['profileImg'];
            $error['imgError'] = $msg;

        } else {
            $imgAfter = $imgBefore;
            $error['imgError'] = $msg;
        }
    }

    // user name validation ((((((done))))))
    if (!($currentUserName === $_POST["userName"])) {
        if (!$VD->validateUserNameEdit($currentUserName, $_POST['userName'], $msg)) {
            $error["userNameError"] = $msg;
        } else if ($db->updateUserData($currentID, 'userName', $_POST["userName"])) {
            $error["userNameError"] = 'username changed';
            $currentUserName = $session->set("userName", $_POST["userName"]);
        }
    }


    // email validation (((((done))))))
    if (!($currentUserEmail === $_POST["emailAddress"])) {
        if (!$VD->validateEmailEdit($currentUserEmail, $_POST['emailAddress'], $msg)) {
            $error['emailAddressError'] = $msg;
        } else if ($db->updateUserData($currentID, 'emailAddress', $_POST["emailAddress"])) {
            $error['emailAddressError'] = 'email address changed ';
            $currentUserEmail = $session->set("emailAddress", $_POST["emailAddress"]);

        }
    }

    // password validation  (((((done))))))
    if (!$VD->validateNewPasswordEdit($oldPassword, $currentUserPassword, $_POST['newPassword'], $msg)) {
        $error['passwordError'] = $msg;

    } else {
        $hashedPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
        if ($db->updateUserData($currentID, 'password', $hashedPassword)) {
            $error['passwordError'] = ' password changed';
            $session->set('password', $_POST['newPassword']);
            $currentUserPassword = $session->get("password");
        }

    }


    if (empty($error)) {
        header('location: profilePage.php');
        return;
    }
}
require 'views/profilePage.view.php';


