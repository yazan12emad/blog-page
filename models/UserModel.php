<?php

namespace app\models;

use app\core\DataBase;
use app\core\Model;
use app\controllers\User;
use PDOException;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;


class UserModel extends Model
{
    public validationClass $validationClass;
    protected string $table = 'UsersInformation';
    private Database $dataBase;
    private UploadFiles $uploadFile;
    private ResetPasswordModel $resetPasswordModel;
    private $user;

    public function __construct()
    {
        $this->dataBase = DataBase::getInstance();
        $this->validationClass = new ValidationClass();
        $this->uploadFile = new UploadFiles();
        $this->resetPasswordModel = new ResetPasswordModel();
        $this->user = User::getInstance();

    }

    public function addUser($userInputData, &$messages)
    {

        $UserName = $userInputData['userName'];
        $emailAddress = $userInputData['emailAddress'];
        $password = $userInputData['password'];

        if (!$this->validationClass->validateUsername($UserName ,$messages)) {
            return false;
        }

        if ($this->getUserInfo($UserName)) {
            $messages = 'Username is already taken';
            return false;
        }

        if (!$this->validationClass->validateEmail($emailAddress)) {
            $messages = 'The User email is required.';
            return false;
        }

        if ($this->getUserInfo($emailAddress)) {
            $messages = 'Email is already taken';
            return false;
        }

        if (!$this->validationClass->validationPassword($password)) {
            $messages = 'The User password is required.';
            return false;
        }

        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            if ($this->insertUserInDataBase($UserName, $emailAddress, $hashedPassword)) {
                return $this->getUserInfo($UserName);
            }
        } catch
        (PDOException $e) {
            $messages = 'error happened';
            return false;
        }
        $messages = 'error happened ';
        return false;
    }

    public function getUserInfo($userData)
    {
        if (filter_var($userData, FILTER_VALIDATE_EMAIL)) {
            return ($this->dataBase->query('SELECT * FROM `UsersInformation` WHERE `emailAddress` = :emailAddress',
                [
                    ':emailAddress' => $userData,
                ]
            )->fetch(\PDO::FETCH_ASSOC));
        }
        return $this->dataBase->query('SELECT * FROM `UsersInformation` WHERE `userName` = :userName',
            [
                ':userName' => $userData,
            ]
        )->fetch(\PDO::FETCH_ASSOC);

    }

    function insertUserInDataBase($UserName, $emailAddress, $password): false|\PDOStatement
    {
        try {
            return $this->dataBase->query(
                "INSERT INTO `{$this->table}` (`userName`, `emailAddress`, `password`)
     VALUES (:userName, :emailAddress, :password)",
                [
                    ':userName' => $UserName,
                    ':emailAddress' => $emailAddress,
                    ':password' => $password,
                ]
            );
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function logInUser($userInputData, &$messages)
    {
        $UserName = $userInputData['userName'];
        $password = $userInputData['password'];

        if (!$this->validationClass->validateUsername($UserName ,$messages)) {
            return false;
        }

        if (!$this->validationClass->validationPassword($password, $messages)) {
            $messages = 'The user password is required ';
            return false;
        }

        if (filter_var($UserName, FILTER_VALIDATE_EMAIL)) {

            if (!$this->validationClass->validateEmail($UserName, $messages)) {
                $messages = 'email address must not be empty ';
                return false;
            }

        }
        $storedUser = $this->getUserInfo(trim($UserName));

        if (!$storedUser) {
            $messages = 'User not found';
            return false;
        }

        if (password_verify(trim($password), $storedUser['password'])) {
            $this->user->setUserInfo($storedUser);
            return $storedUser;

        }
        if (trim($password) == $storedUser['password']) {
            $this->user->setUserInfo($storedUser);
            return $storedUser;
        }

        $messages = 'password not matched';
        return false;

    }

    public function saveProfileChanges($userCurrentData, $userInputData, &$messages, &$profileChanges)
    {
        $imgData = $userInputData['Image'];

        try {
            if (isset($imgData) && !empty($imgData['tmp_name'])) {
                if ($this->changeProfileImage($userCurrentData['id'], $imgData,
                    $messages['ProfileImageMessage'])) {
                    $profileChanges['profileImg'] = $this->getUserImg($userCurrentData['id'])['profileImg'];

                }
            }

            if (!empty($userInputData['userName'] && $userInputData['userName'] !== $userCurrentData['userName'])) {
                if ($this->changeProfileUserName($userCurrentData['id'],
                    $userInputData['userName'],
                    $userCurrentData['userName'],
                    $messages['userNameMessage'])) {
                    $profileChanges['userName'] = $userInputData['userName'];
                }
            }


            if ($userCurrentData['emailAddress'] !== $userInputData["emailAddress"]) {
                if ($this->changeProfileEmail($userCurrentData['id'],
                    $userInputData['emailAddress'],
                    $userCurrentData['emailAddress'],
                    $messages['emailAddressMessage'])) {
                    $profileChanges['emailAddress'] = $userInputData['emailAddress'];
                }

            }
            if (!empty($userInputData['currentPassword']) && !empty($userInputData['newPassword']) && $userInputData['newPassword'] !== $userCurrentData['password']) {
                if ($this->changeProfilePassword($userCurrentData['id'],
                    $userCurrentData['password'],
                    $userInputData['currentPassword'],
                    $userInputData['newPassword'],
                    $messages['passwordMessage'])) {
                    return true;
                }

            }
            if (empty($profileChanges)) {
                $profileChanges['systemMessage'] = 'There is no changes made';
            }
        } catch (PDOException $e) {
            $profileChanges['systemMessage'] = 'error while change profile Data please try again later';
            return false;
        }

        return true;

    }

    public function changeProfileImage($currentID, $file, &$messages)
    {

        $newImg = $this->uploadFile->addImg($file, $messages);
        if ($newImg !== false) {
            $this->updateUserData($currentID, 'profileImg', $newImg);
            return true;
        }
        return false;

    }

    public function updateUserData($id, $key, $newValue , &$message = null)
    {
        if(!$this->validationClass->notEmpty($newValue)){
            $message = 'this field is required';
            return false;

        }
        try {
            if ($this->dataBase->query("UPDATE `UsersInformation` SET $key = :value WHERE id = :id", [
                ':value' => trim($newValue),
                ':id' => $id
            ])) {
                return true;
            }
                return false;
        } catch (PDOException $e) {
            $message = 'Error while updating user '. $key;
            return false;
        }

    }

    public function getUserImg($id)
    {
        return $this->dataBase->query(
            'SELECT `profileImg` from UsersInformation WHERE `id` = :id',
            [
                ':id' => $id,
            ]
        )->fetch(\PDO::FETCH_ASSOC);


    }

    public function changeProfileUserName($currentID, $userName, $currentUserName, &$messages)
    {

        if (!$this->validationClass->validateUserNameEdit($currentUserName, $userName, $messages)){
            return false;
        }
        if ($this->checkUserData('userName', $userName)) {
            $messages = 'Username is already taken';
            return false;
        }
        if ($this->updateUserData($currentID, 'userName', $userName)) {
            $messages = 'User name changed successfully';
            return $userName;
        }
        $messages = 'error happened';
        return false;


    }

    public function checkUserData($key, $value): bool
    {
        try {


            $dataExist = $this->dataBase->query('SELECT * FROM `UsersInformation` WHERE ' . $key . ' = :value',
                [
                    ':value' => trim($value),
                ]
            )->fetch(\PDO::FETCH_ASSOC);

            return (bool)$dataExist;
        } catch (\PDOException $e) {
            return 'error in checking user data';
        }

    }

    public function changeProfileEmail($currentID, $emailAddress, $currentUserEmail, &$messages)
    {
        if (!$this->validationClass->validateEmailEdit($currentUserEmail, $emailAddress)) {
            $messages = 'Invalid email address';
            return false;
        }

        if ($this->checkUserData('emailAddress', $emailAddress)) {
            $messages = 'Email is already taken';
            return false;
        }

        if ($this->updateUserData($currentID, 'emailAddress', $emailAddress)) {
            $messages = 'Email changed successfully';
            return $emailAddress;
        }
    }

    public function changeProfilePassword($currentID, $oldPassword, $currentPassword, $newPassword, &$messages)
    {
        if (!$this->validationClass->validateNewPasswordEdit($oldPassword, $currentPassword, $newPassword, $messages)) {
            return false;
        }
        if ($this->updateUserData($currentID, 'password', password_hash($newPassword, PASSWORD_DEFAULT))) {
            $messages = 'Password updated successfully';
            return true;
        }
        $messages['generalError'] = 'error happened';
        return false;
    }

    public function forgetPassword($emailAddress)
    {
        if (!$this->validationClass->validateEmail($emailAddress)) {
            return ['success' => false, 'statusMessage' => 'Please enter a valid email address'];
        }

        try {
            $userInfo = $this->getUserInfo($emailAddress);
        } catch (PDOException $e) {

            return ['success' => false, 'statusMessage' => 'error happened'];
        }

        if (!$userInfo) {
            return ['success' => false, 'statusMessage' => 'error happened'];
        }

        // generate token
        try {
            $UserToken = bin2hex(random_bytes(4));

        } catch (\Exception $e) {

            return ['success' => false, 'statusMessage' => 'error happened while resetting password'];
        }

        $expireTokenDate = date("Y-m-d H:i:s", strtotime("+30 minutes"));

        try {
            $this->resetPasswordModel->saveResetToken($userInfo['id'], $UserToken, $expireTokenDate);
        } catch (PDOException $e) {

            return ['success' => false, 'statusMessage' => 'error happened while resetting password'];
        }

        $resetLink = 'http:/yazan.test/submitNewPassword';

        try {
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

            $mail->setFrom('no-reply@yourapp.com', 'Your App');
            $mail->addAddress($emailAddress);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "
        <p>Hi,</p>
        <p>You requested to reset your password. Click the link below:</p>
        <p><a href='$resetLink'>$resetLink</a></p>
                <p> your reset code is '$UserToken' </p>
        <p>This link will expire in 30 minutes.</p>
    ";
            $mail->send();

        } catch (Exception $e) {

            return ['success' => false, 'statusMessage' => "Email could not be sent. Mailer Error: {$mail->ErrorInfo}"];
        }

        return ['success' => true, 'id' => $userInfo['id'], 'statusMessage' => 'message sent to your email '];
    }

    public function submitNewPassword($userDataInput, $userId)
    {
        $inputToken = trim($userDataInput['token']);
        $newPassword = trim($userDataInput['newPassword']);
        $confirmNewPassword = trim($userDataInput['confirmPassword']);
        $savedToken = $this->resetPasswordModel->getToken($userId);

        if (!$savedToken) {
            return ['success' => false, 'statusMessage' => 'Token not found or already used'];
        }
        if ($savedToken['token'] !== $inputToken) {
            return ['success' => false, 'statusMessage' => 'Invalid token'];
        }
        if (!$this->validationClass->validationPassword($newPassword , $messages)) {
            return ['success' => false, 'statusMessage' => $messages];
        }
        if (!$this->validationClass->validationPassword($confirmNewPassword)) {
            return ['success' => false, 'statusMessage' => $messages];
        }
        if ($newPassword !== $confirmNewPassword) {
            return ['success' => false, 'statusMessage' => 'New password and Confirm password do not match'];
        }

        try {

            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            if ($this->updateUserData($userId, 'password', $hashedPassword)) {

                if ($this->resetPasswordModel->markTokenAsUsed($userId, $savedToken['token'])) {
                    return ['success' => true, 'statusMessage' => 'Password updated successfully'];
                }
            }

                return ['success' => false, 'statusMessage' => 'Password not changed'];
        } catch (\Exception $e) {
            return ['success' => false, 'statusMessage' => 'error happened'];
        }
    }

    public function getUserDataById($userID)
    {
        $userData = $this->dataBase->query('SELECT * FROM `UsersInformation` WHERE `id` = :userId', [
            ':userId' => $userID,
        ])->fetch(\PDO::FETCH_ASSOC);

        if (!$userData) {
            throw new \DomainException('User not found');
        }
        return $userData;
    }

    public function getAllUsersData(): array
    {
        return ($this->dataBase->query('SELECT id , userName , emailAddress ,user_role FROM `UsersInformation` ')->fetchAll(\PDO::FETCH_ASSOC));

    }

    public function deleteUser($userId, &$msg)
    {
        try {
            if ($this->dataBase->query("DELETE FROM UsersInformation where id =:id", [
                    ':id' => $userId
                ]

            )) {
                return true;
            }
            return false;
        } catch (\PDOException $e) {
            $msg = $e->getMessage();
            return false;
        }
    }


}
