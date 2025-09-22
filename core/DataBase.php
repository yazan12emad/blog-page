<?php


namespace app\core;
if (!defined('SECURE_BOOT')) {
    header('Location: ../');
    die('Direct access is not permitted.');
}

use app\Exception;
use app\PDO;

class DataBase
{
    public $connection;


    public function __construct()
    {

        $config = require('keys.php');
        $DataBaseKeys = $config['DataBase'];

        $dsnString = "mysql:host={$DataBaseKeys['host']};dbname={$DataBaseKeys['dbname']};charset=utf8";
        $this->connection = new \PDO($dsnString, $dsn['user'] ?? 'root', $dsn['pass'] ?? '');

        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function query($query, $params = [])
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params); // pass parameters!
        return $stmt;
    }


    public function addNewUser($UserName, $emailAddress, $password)
    {
        return ($this->query('INSERT INTO `UsersInformation`(`userName`, `emailAddress`, `password`)
             VALUES (:userName, :emailAddress, :password)',
            [
                ':userName' => $UserName,
                ':emailAddress' => $emailAddress,
                ':password' => $password,
            ]));

    }

    public function updateUserData($id, $key, $newValue)
    {

        $allowedColumns = ['userName', 'emailAddress', 'password' , 'profileImg'];
        if (!in_array($key, $allowedColumns)) {
            throw new Exception("Invalid column name");
        }
        // Prepare safe SQL
        $this->query("UPDATE `UsersInformation` SET $key = :value WHERE id = :id", [
            ':value' => trim($newValue),
            ':id' => $id
        ]);
        return true;
    }

    public function saveResetToken($userData, $token, $expireDate): void
    {
        $this->query('INSERT INTO `reset_tokens`(`user_id`, `token`, `expired_at`)VALUES (:user_id , :token , :expireDate) ',
            [
                'user_id' => $userData,
                ':token' => $token,
                ':expireDate' => $expireDate,
            ]);


    }

    public function getToken($userId)
    {
        $tokenData = $this->query(
            'SELECT `token` FROM `reset_tokens` WHERE `user_id` = :userId  AND expired_at >= NOW() AND used = 0 ORDER BY expired_at DESC LIMIT 1',
            [
                ':userId' => $userId,
            ]
        )->fetch(\PDO::FETCH_ASSOC);


        return $tokenData;
    }

    public function markTokenAsUsed($userId, $token)
    {

        $this->query('UPDATE `reset_tokens` SET used = used + 1 WHERE user_id = :userId AND token = :token',
            [
                ':userId' => $userId,
                ':token' => $token,
            ]
        );
    }



    public function getUserInfo($userName)
    {
        return $this->query('SELECT * FROM `UsersInformation` WHERE `userName` = :userName',
            [
                ':userName' => $userName,
            ]
        )->fetch(\PDO::FETCH_ASSOC);

    }

    public function getUserEmail($emailAddress)
    {
        return ($this->query('SELECT * FROM `UsersInformation` WHERE `emailAddress` = :emailAddress',
            [
                ':emailAddress' => $emailAddress,
            ]
        )->fetch(\PDO::FETCH_ASSOC));

    }


    public function checkUserData($key, $value): bool
    {
        $dataExist = $this->query('SELECT * FROM `UsersInformation` WHERE ' . $key . ' = :value',
            [
                ':value' => trim($value),
            ]
        )->fetch(\PDO::FETCH_ASSOC);


        return (bool)$dataExist;

    }




    public function getUserImg($id)
    {
        return  $this->query(
            'SELECT `profileImg` from UsersInformation WHERE `id` = :id',
            [
            ':id' => $id,
            ]
    )->fetch(\PDO::FETCH_ASSOC);


    }


}
