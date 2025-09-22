<?php

namespace app\models;

if (!defined('SECURE_BOOT')) {
    header('Location: ../');
    die('Direct access is not permitted.');
}
use app\core\DataBase;
use app\core\session;



class ValidationClass
{
    protected $db;     //Remove DB
    protected $session;

    public function __construct()
    {
        $this->db = new DataBase();

        $this->session = new Session();

    }


    public function notEmpty($value): bool
    {
        if (empty($value)) {
            return false;
        }
        if (strlen(trim($value)) === 0) {
            return false;
        }
        return true;
    }

    public function validateUserNameEdit($currentUserName, $newUserName, &$msg = null): bool
    {
        if (!$this->notEmpty($newUserName)) {
            $msg = 'user name must at least contain letters and numbers';
            return false;
        } else if ($currentUserName == $newUserName) {
            $msg = 'Its the same user name ';
            return false;
        } else if ($this->db->checkUserData('userName', $newUserName)) {
            $msg = 'User name already used ';
            return false;
        } else
            return true;

    }

    function validateEmail($emailAddress): bool
    {

        if (!$this->notEmpty($emailAddress))
            return false;
        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL))
            return false;

        else
            return true;

    }

    public function validateEmailEdit($currentUserEmail, $emailAddress, &$msg = null): bool|string
    {
        if (!$this->validateEmail($emailAddress)) {
            $msg = 'email address is Invalid';
            return false;
        } else if ($currentUserEmail === $emailAddress) {
            $msg = 'email address is the same email';
            return false;
        } else if ($this->db->checkUserData('emailAddress', $emailAddress)) {
            $msg = 'email address already used';
            return false;
        } else {

            return true;
        }

    }

    public function validationPassword($password): bool
    {
        if (!$this->notEmpty($password))
            return false;
        else if (strlen($password) <= 7)
            return false;
        else
            return true;
    }


    public function validateNewPasswordEdit($oldPassword, $currentPassword, $newPassword, &$msg = null)
    {

        if (!$this->validationPassword($currentPassword)) {
            $msg = " current password is invalid";
            return false;
        } else if (!$this->validationPassword($newPassword)) {
            $msg = 'new password is invalid';
            return false;
        } else if ($oldPassword !== $currentPassword) {
            $msg = ' current password is Incorrect';;
            return false;
        } else if ($currentPassword === $newPassword) {
            $msg = ' new password is the same old password';
            return false;
        } else
            return true;
    }

}










