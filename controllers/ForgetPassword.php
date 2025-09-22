<?php

require_once __DIR__ . '/../vendor/autoload.php';

use app\core\DataBase;
use app\core\session;
use app\models\ValidationClass;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$VC = new ValidationClass();
$db = new DataBase();
$session = new Session();

$config = require('keys.php');
$SMTPKeys = $config['SMTP'];

$heading = "forget password ";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $emailAddress = $_POST['emailAddress'];

    if (!$VC->validateEmail($_POST['emailAddress'])) {
        $error['forgetPasswordError'] = "Please enter a valid email address";
    } else if (!$userInfo = $db->getUserEmail($emailAddress)) {
        $error['forgetPasswordError'] = "email not found";

    } else {
        try {
            $session->setSessionData($userInfo);


            $token = bin2hex(random_bytes(4));
            $expireDate = date("Y-m-d H:i:s", strtotime("+30 minutes"));
            $db->saveResetToken($userInfo['id'], $token, $expireDate);
            $resetLink = 'http://localhost:8000/submitNewPassword.php';

            try {
                $mail = new PHPMailer(true);

                // set SMTP
                $mail->isSMTP();
                $mail->Host = 'sandbox.smtp.mailtrap.io';
                $mail->SMTPAuth = true;
                $mail->Username = 'bba99b64486755';
                $mail->Password = 'c7e0becec9c2d9';
                $mail->Port = 2525;

//            $mail->Host = $SMTPKeys['Host'];
//            $mail->SMTPAuth = true;
//            $mail->Username = $SMTPKeys['Username'];
//            $mail->Password = $SMTPKeys['Password'];
//            $mail->Port = $SMTPKeys['Port'];

                $mail->setFrom('no-reply@yourapp.com', 'Your App');
                $mail->addAddress($emailAddress);
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "
        <p>Hi,</p>
        <p>You requested to reset your password. Click the link below:</p>
        <p><a href='$resetLink'>$resetLink</a></p>
                <p> your reset code is '$token' </p>
        <p>This link will expire in 30 minutes.</p>
    ";

                $mail->send();


                $session->set('success', 'Reset link sent to your email!');
                echo $session->get('success');
            } catch (Exception $e) {
                $error['forgetPasswordError'] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

        } catch (\Random\RandomException $e) {

        }
        exit;
    }
}


require 'views/ForgetPassword.View.php';