<?php

namespace app\models;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class SendEmail
{
       public array $Links =[
           'resetLink' => 'http://yazan.test/submitNewPassword' ,
       ];

       public function CreateSMTP()
       {
               $mail = new PHPMailer(true);
               $config = require('keys.php');
               $SMTPKeys = $config['SMTP'];

               // set SMTP (Simple Mail Transfer Protocol)
               $mail->isSMTP();
               $mail->Host = $SMTPKeys['Host'];
               $mail->SMTPAuth = true;
               $mail->Username = $SMTPKeys['Username'];
               $mail->Password = $SMTPKeys['Password'];
               $mail->Port = $SMTPKeys['Port'];
               $mail->CharSet = 'UTF-8';
               $mail->isHTML(true);

               return $mail;
       }


    public function SendEmailBySMTP($emailAddress , $subject , $body){

        $mail = $this->CreateSMTP();

        try {
        $mail->setFrom('no-reply@yourapp.com', 'Your App');
        $mail->addAddress($emailAddress , 'User');
        $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
        return $mail->send();
            }
        catch (Exception $e) {
            error_log( "Message could not be sent. Mailer Error:" . $mail->ErrorInfo);
            return false;
        }

    }

public function sendResetEmail($emailAddress ,$UserToken)
{
        $Subject = 'Password Reset Request';
        $Body = "
        <p>Hi,</p>
        <p>You requested to reset your password. Click the link below:</p>
        <p><a href=\"{$this->Links['resetLink']}\">Reset Link</a></p>
                <p> your reset code is '$UserToken' </p>
        <p>This link will expire in 30 minutes.</p>
    ";

    return $this->SendEmailBySMTP($emailAddress, $Subject, $Body);
}






}