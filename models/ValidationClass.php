<?php

namespace app\models;


use app\core\session;


class ValidationClass
{
    protected Session $session;

    public function __construct()
    {
        $this->session = Session::getInstance();

    }


    public function notEmpty($value , &$messages = []): bool
    {
        if (empty($value) || strlen(trim($value)) === 0) {
            $messages = 'This field is required.';
            return false;
        }
        return true;
    }

    public function validateUserNameEdit($currentUserName, $newUserName, &$messages =[] ): bool
    {
        if (!$this->notEmpty($newUserName)) {
            $messages['UserNameError'] = 'user name must at least contain letters and numbers';
            return false;
        } else if ($currentUserName == $newUserName) {
            $messages['UserNameError'] = 'Its the same user name ';
            return false;
        } else
            return true;

    }

    function validateEmail($emailAddress , &$messages=[]): bool
    {

        if ((!$this->notEmpty($emailAddress)) || (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL))) {
            $messages['emailAddressError'] = 'email address must not be empty';
            return false;
        }
            return true;

    }

    public function validateEmailEdit($currentUserEmail, $emailAddress, &$messages =[] ): bool|string
    {
        if (!$this->validateEmail($emailAddress)) {
            $messages['emailAddressError'] = 'email address is Invalid';
            return false;
        } else if ($currentUserEmail === $emailAddress) {
            $messages['emailAddressError'] = 'email address is the same email';
            return false;
        } else   {

            return true;
        }

    }

    public function validationPassword($password , &$messages = null): bool
    {
        if (!$this->notEmpty($password) || strlen($password) < 7){
            $messages['passwordError'] = 'Password must be at least 8 characters long';
            return false;
    }
            return true;
    }


    public function validateNewPasswordEdit($oldPasswordHash, $currentPasswordInput, $newPasswordInput, &$messages = null): bool
    {
        if (!$this->validationPassword($currentPasswordInput)) {
            $messages['currentPasswordError'] = "Password must be at least 7 characters long";
            return false;
        }
        else if (!$this->validationPassword($newPasswordInput)) {
            $messages['newPasswordError'] = 'New password format is invalid';
            return false;
        }
        else if (password_verify($currentPasswordInput, $oldPasswordHash)) {
            $messages['currentPasswordError'] = 'Current password is incorrect';
            return false;
        }
        else if (password_verify($newPasswordInput, $oldPasswordHash)) {
            $messages['newPasswordError'] = 'New password cannot be the same as old password';
            return false;
        }
        else {
            return true;
        }
    }


}










