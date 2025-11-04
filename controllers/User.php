<?php
namespace app\controllers;

// CRUD
//create , read , update , delete
//

class User  {

//    private $userId;
//    private $userName;
//    private $emailAddress;
//    private $password;

    private static  $instance;
    private array $User = [];

    private function __construct()
    {

    }
    public static function getInstance(): User
    {
    if (self::$instance === null) {
        self::$instance = new self();
    }
    return self::$instance;
}

    public function getUserInfo(): array
    {
        return $this->User;
    }

    public function setUserInfo($userData):void
    {
        $this->User =[
            'id' => $userData['id'],
            'userName' => $userData['userName'],
            'emailAddress' => $userData['emailAddress'],
            'password' => $userData['password'],

        ];

    }

    public function editUserInfo(): array
    {
        return $this->User;

    }

    public function deleteUserInfo(): void
    {
        $this->User =[];
    }



}
