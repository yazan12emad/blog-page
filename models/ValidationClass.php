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


    public function notEmpty($value , &$messages = null): bool
    {
        if (empty($value) || strlen(trim($value)) === 0 ) {
            $messages = 'This field is required.';
            return false;
        }
        return true;
    }

    public function regularExpression($pattern , $value){

         if(preg_match($pattern, $value)){
             return false;
         }
         return true;
    }

    public function validateCategoryData($categoryName ,$categoryDescription , &$messages){
        if(!$this->notEmpty($categoryName , $messages)){
            $messages = 'The category name field is required.';
            return false;
        }
        if(!$this->notEmpty($categoryDescription , $messages)){
            $messages = 'The category description field is required.';
            return false;
        }
        $categoryNamePregPattern = '/^[a-zA-Z0-9_\W+]{3,20}$/';

        if ($this->regularExpression($categoryNamePregPattern, $categoryName)) {
            $messages = 'Invalid category name format , category name must contain letters and less than 20 characters.';
            return false;
        }
        $categoryDescriptionPregPattern = '/^[a-zA-Z0-9_\W+]{3,50}$/';

        if ($this->regularExpression($categoryDescriptionPregPattern, $categoryDescription)){
            $messages = 'Invalid category description format , description must contain letters and less than 50 characters.';
        return false;
        }
        return true;
    }
    public function validateUsername($username , &$messages): bool{
    if (!$this->notEmpty($username, $messages))
        return false;

        $userNamePregPattern = '/^[a-zA-Z0-9]{3,20}$/';

    if (!$this->regularExpression($userNamePregPattern, $username)) {
        $messages = 'Invalid username format , user name must contain letters , less than 20 characters.';
        return false;
    }
    return true;
}

    public function validateUserNameEdit($currentUserName, $newUserName, &$messages =[] ): bool
    {
        if ($currentUserName === $newUserName) {
            $messages = 'Its the same user name ';
            return false;
        }

        if (!$this->validateUsername($currentUserName , $messages)){
            return false;
        }
            return true;

    }

    function validateEmail($emailAddress , &$messages=[]): bool
    {

        if (!$this->notEmpty($emailAddress) || (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL))) {
            $messages = 'email address must not be empty';
            return false;
        }
        if(strlen($emailAddress) > 100){
            $messages = 'email address must be less than 100 characters';
            return false;
        }
            return true;

    }

    public function validateEmailEdit($currentUserEmail, $emailAddress, &$messages =null ): bool|string
    {

        if (!$this->validateEmail($emailAddress)) {
            $messages['emailAddressError'] = 'email address is Invalid';
            return false;
        }
        if ($currentUserEmail === $emailAddress) {
            $messages['emailAddressError'] = 'email address is the same email';
            return false;
        }
            return true;

    }

    public function validationPassword($password , &$messages = null): bool
    {
        $passwordPregPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^a-zA-Z0-9\s])\S{8,20}$/';

        if ($this->regularExpression($passwordPregPattern, $password)){
            $messages = 'Invalid password format , password must contain big letter , numbers , symbols ,and more than 8 characters.';
        }
            return true;
    }


    public function validateNewPasswordEdit($oldPasswordHash, $currentPasswordInput, $newPasswordInput, &$messages = null): bool
    {
        if (!$this->validationPassword($currentPasswordInput)) {
            $messages =  'current password format is invalid';
            return false;
        }
         if (!$this->validationPassword($newPasswordInput)) {
             $messages =  'New password format is invalid';
             return false;
        }
        if (!password_verify($currentPasswordInput, $oldPasswordHash)) {
             $messages = 'Current password is incorrect';
             return false;
        }
         if (password_verify($newPasswordInput, $oldPasswordHash)) {
             $messages = 'New password cannot be the same as old password';
             return false;
        }

            return true;

    }


}










