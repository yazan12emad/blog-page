<?php

namespace app\models;

use app\core\Model;
use app\controllers\User;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;


class UserModel extends Model
{
    public $ValidationClass;

    private $uploadFile;

    private $User;


    protected string $table = 'UsersInformation';

    public function __construct()
    {
        $this->ValidationClass = new ValidationClass();
        $this->uploadFile = new UploadFiles();
        $this->User = User::getInstance();

    }

    public function addUser($UserName, $emailAddress, $password, &$msg = [])
    {

        if (!$this->ValidationClass->notEmpty($UserName, $msg)) {
            return $msg;
        } else if ($this->getUserInfo($UserName)) {
            return $msg['UserNameError'] = 'Username is already taken';
        }
        if (!$this->ValidationClass->validateEmail($emailAddress, $msg)) {
            return $msg;
        } else if ($this->getUserInfo($emailAddress)) {
            return $msg['emailAddressError'] = 'Email is already taken';
        }
        if (!$this->ValidationClass->validationPassword($password, $msg)) {
            return $msg;
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if ($this->insertUserInDataBase($UserName, $emailAddress, $hashedPassword)) {
            //$this->User->setUserInfo($this->getUserInfo($UserName));

            return $this->getUserInfo($UserName);
        } else
            $msg['generalError'] = 'error happened ';
        return false;
    }

    public function logInUser($UserName, $password, &$msg = [])
    {
        if (!$this->ValidationClass->notEmpty($UserName, $msg)) {
            return false;
        } else if (!$this->ValidationClass->validationPassword($password, $msg)) {
            return false;
        } else {
            if (filter_var($UserName, FILTER_VALIDATE_EMAIL)) {
                if (!$this->ValidationClass->validateEmail($UserName, $msg))
                    return false;
                else
                    $user = $this->getUserInfo($UserName);
            } else {
                $user = $this->getUserInfo($UserName);
            }
            if ($user && password_verify($password, $user['password'])) {
                $this->User->setUserInfo($user);
                return $user;

            } else if (($user['password'] ?? false) === $password) {
                $this->User->setUserInfo($user);
                return $user ;
            } else {
                $msg['passwordError'] = 'password not matched';
                return false;
            }
        }
    }

    public function changeProfile($currentID, $file)
    {
        //   change img    //
        $newImg = $this->uploadFile->addImg($file, $msg);

        if ($newImg !== false) {
            $this->updateUserData($currentID, 'profileImg', $newImg);
            return $this->getUserImg($currentID)['profileImg'];
        } else
            return false;

    }

    public function changeProfileUserName($currentID, $userName, $currentUserName, &$msg = [])
    {
        //  change userName   //
        if (!$this->ValidationClass->validateUserNameEdit($currentUserName, $userName, $msg))
            return false;

        else if ($this->checkUserData('userName', $userName)) {
            $msg['userNameError'] = 'Username is already taken';
            return false;
        } else if ($this->updateUserData($currentID, 'userName', $userName)) {
            $msg['userNameError'] = 'User name changed successfully';
            return $userName;

        } else {
            $msg['generalError'] = 'error happened';
            return false;
        }

    }

    public function changeProfileEmail($currentID, $emailAddress, $currentUserEmail, &$msg = [])
    {
        if (!$this->ValidationClass->validateEmailEdit($currentUserEmail, $emailAddress, $msg)) {
            return false;

        } else if ($this->checkUserData('emailAddress', $emailAddress)) {
            $msg['emailAddressError'] = 'Email is already taken';
            return false;

        } else if ($this->updateUserData($currentID, 'emailAddress', $emailAddress)) {
            $msg['emailAddressError'] = 'Email changed successfully';
            return $emailAddress;
        }
    }

    public function changeProfilePassword($currentID, $oldPassword, $currentPassword, $newPassword, &$msg = []): false
    {
        if (!$this->ValidationClass->validateNewPasswordEdit($oldPassword, $currentPassword, $newPassword, $msg)) {
            return false;

        }
        if ($this->updateUserData($currentID, 'password', password_hash($newPassword, PASSWORD_DEFAULT))) {
            $msg['newPasswordError'] = 'Password updated successfully';
            return $newPassword;
        } else
            $msg['generalError'] = 'error happened';
        return false;


    }

    public function forgetPassword($emailAddress, &$msg = [])
    {

        $userInfo = $this->getUserInfo($emailAddress);
        if (!$this->ValidationClass->validateEmail($emailAddress)) {
            $msg['forgetPasswordError'] = 'Please enter a valid email address';
            return false;
        } else if (!$userInfo) {
            $msg['forgetPasswordError'] = 'email address does not exist';
            return false;
        } else {
            $token = bin2hex(random_bytes(4));
            $expireDate = date("Y-m-d H:i:s", strtotime("+30 minutes"));
            (new ResetPasswordModel)->saveResetToken($userInfo['id'], $token, $expireDate);
            $resetLink = 'http:/yazan.test/submitNewPassword';

            try {
                $mail = new PHPMailer(true);

                $config = require('keys.php');
                $SMTPKeys = $config['SMTP'];

                // set SMTP
                $mail->isSMTP();
                $mail->Host = $SMTPKeys['Host'];
                $mail->SMTPAuth = true;
                $mail->Username = $SMTPKeys['Username'];
                $mail->Password = $SMTPKeys['Password'];
                $mail->Port = $SMTPKeys['Port'];

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

            } catch (Exception $e) {

                $msg['forgetPasswordError'] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                return false;

            }
            $msg['forgetPasswordError'] = 'message sent';
            return $userInfo;
        }

    }

    /**
     * @throws Exception
     */
    public function submitNewPassword($currentID , $inputToken , $newPassword, $confirmPassword, $savedToken, &$msg)
    {
        if (!$savedToken) {
            $msg['submitNewPasswordTokenError'] = 'Token not found or already used';
            return false;
        } else if ($savedToken['token'] !== $inputToken) {
            $msg['submitNewPasswordTokenError'] = 'Invalid token';
            return false;
        } else if (!$this->ValidationClass->validationPassword($newPassword)) {
            $msg['submitNewPasswordError'] = 'Invalid new password';

            return false;

        } else if (!$this->ValidationClass->validationPassword($confirmPassword)) {
            $msg['submitNewPasswordConfirmPasswordError'] = 'Confirm password is invalid';
            return false;

        }
        else if ($newPassword !== $confirmPassword) {
            $msg['submitNewPasswordError'] = 'New password and Confirm password do not match';
            return false;
        }
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        if ($this->updateUserData($currentID, 'password', $hashedPassword)) {
            (new ResetPasswordModel)->markTokenAsUsed($currentID, $savedToken['token']);
            $msg['submitNewPasswordPassChange'] = 'Password updated successfully';
            return true;
        }
        else
            $msg['submitNewPasswordError'] = 'Password not changed';
        return false;

    }

    function insertUserInDataBase($UserName, $emailAddress, $password): false|\PDOStatement
    {
        return $this->getDb()->query(
            "INSERT INTO `{$this->table}` (`userName`, `emailAddress`, `password`)
     VALUES (:userName, :emailAddress, :password)",
            [
                ':userName' => $UserName,
                ':emailAddress' => $emailAddress,
                ':password' => $password,
            ]
        );
    }


    public function getUserInfo($userData)
    {
        if (filter_var($userData, FILTER_VALIDATE_EMAIL)) {
            return ($this->getDb()->query('SELECT * FROM `UsersInformation` WHERE `emailAddress` = :emailAddress',
                [
                    ':emailAddress' => $userData,
                ]
            )->fetch(\PDO::FETCH_ASSOC));

        } else {
            return $this->getDb()->query('SELECT * FROM `UsersInformation` WHERE `userName` = :userName',
                [
                    ':userName' => $userData,
                ]
            )->fetch(\PDO::FETCH_ASSOC);

        }
    }

    public function getUserFullInfo(): array
    {
            return ($this->getDb()->query('SELECT * FROM `UsersInformation` ')->fetchAll(\PDO::FETCH_ASSOC));

        }


    public function checkUserData($key, $value): bool
    {
        $dataExist = $this->getDb()->query('SELECT * FROM `UsersInformation` WHERE ' . $key . ' = :value',
            [
                ':value' => trim($value),
            ]
        )->fetch(\PDO::FETCH_ASSOC);

        return (bool)$dataExist;

    }

    public function getUserImg($id)
    {
        return $this->getDb()->query(
            'SELECT `profileImg` from UsersInformation WHERE `id` = :id',
            [
                ':id' => $id,
            ]
        )->fetch(\PDO::FETCH_ASSOC);


    }

    public function updateUserData($id, $key, $newValue)
    {
        if($this->getDb()->query("UPDATE `UsersInformation` SET $key = :value WHERE id = :id", [
            ':value' => trim($newValue),
            ':id' => $id
        ]))
        return true;
        else
            return   throw new Exception("Invalid column name");

    }

    public function deleteUser($userId):bool
    {
         if($this->getDb()->query("DELETE FROM UsersInformation where id =:id" , [
                ':id' => $userId
            ]

        ))
             return true;
         return false;
    }

}
