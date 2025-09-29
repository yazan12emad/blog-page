<?php

namespace app\models;
use app\core\Model;


class UserModel extends Model
{

    protected string $table = 'UsersInformation';
    public function addNewUser($UserName, $emailAddress, $password)
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

    public function getUserInfo($userName)
    {
        return $this->getDb()->query('SELECT * FROM `UsersInformation` WHERE `userName` = :userName',
            [
                ':userName' => $userName,
            ]
        )->fetch(\PDO::FETCH_ASSOC);

    }

    public function getUserEmail($emailAddress)
    {
        return ($this->getDb()->query('SELECT * FROM `UsersInformation` WHERE `emailAddress` = :emailAddress',
            [
                ':emailAddress' => $emailAddress,
            ]
        )->fetch(\PDO::FETCH_ASSOC));

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
        return  $this->getDb()->query(
            'SELECT `profileImg` from UsersInformation WHERE `id` = :id',
            [
                ':id' => $id,
            ]
        )->fetch(\PDO::FETCH_ASSOC);


    }

    public function updateUserData($id, $key, $newValue)
    {

        $allowedColumns = ['userName', 'emailAddress', 'password' , 'profileImg'];
        if (!in_array($key, $allowedColumns)) {
            throw new Exception("Invalid column name");
        }
        // Prepare safe SQL
        $this->getDb()->query("UPDATE `UsersInformation` SET $key = :value WHERE id = :id", [
            ':value' => trim($newValue),
            ':id' => $id
        ]);
        return true;
    }


    public function deleteUser($userId){

    }
}