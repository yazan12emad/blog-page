<?php

namespace app\models;

if (!defined('SECURE_BOOT')) {
    header('Location: ../');
    die('Direct access is not permitted.');
}

use app\core\session;



class ValidationClass
{
    protected $session;

    public function __construct()
    {
        $this->session = Session::getInstance();

    }


    public function notEmpty($value , &$msg = []): bool
    {
        if (empty($value) ||strlen(trim($value)) === 0) {
            $msg = 'This field is required.';
            return false;
        }

        return true;
    }

    public function validateUserNameEdit($currentUserName, $newUserName, &$msg =[] ): bool
    {
        if (!$this->notEmpty($newUserName)) {
            $msg['UserNameError'] = 'user name must at least contain letters and numbers';
            return false;
        } else if ($currentUserName == $newUserName) {
            $msg['UserNameError'] = 'Its the same user name ';
            return false;
        } else
            return true;

    }

    function validateEmail($emailAddress , &$msg=[]): bool
    {

        if ((!$this->notEmpty($emailAddress)) || (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL))) {
            $msg['emailAddressError'] = 'email address must not be empty';
            return false;
        }
            return true;

    }

    public function validateEmailEdit($currentUserEmail, $emailAddress, &$msg =[] ): bool|string
    {
        if (!$this->validateEmail($emailAddress)) {
            $msg['emailAddressError'] = 'email address is Invalid';
            return false;
        } else if ($currentUserEmail === $emailAddress) {
            $msg['emailAddressError'] = 'email address is the same email';
            return false;
        } else   {

            return true;
        }

    }

    public function validationPassword($password , &$msg = null): bool
    {
        if (!$this->notEmpty($password) || strlen($password) < 7){
            $msg['passwordError'] = 'Password must be at least 8 characters long';
            return false;
    }
            return true;
    }


    public function validateNewPasswordEdit($oldPasswordHash, $currentPasswordInput, $newPasswordInput, &$msg = null): bool
    {
        if (!$this->validationPassword($currentPasswordInput)) {
            $msg['currentPasswordError'] = "Password must be at least 7 characters long";
            return false;
        }
        else if (!$this->validationPassword($newPasswordInput)) {
            $msg['newPasswordError'] = 'New password format is invalid';
            return false;
        }
        else if (password_verify($currentPasswordInput, $oldPasswordHash)) {
            $msg['currentPasswordError'] = 'Current password is incorrect';
            return false;
        }
        else if (password_verify($newPasswordInput, $oldPasswordHash)) {
            $msg['newPasswordError'] = 'New password cannot be the same as old password';
            return false;
        }
        else {
            return true;
        }
    }


}










