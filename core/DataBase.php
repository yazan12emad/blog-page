<?php


namespace app\core;



class DataBase
{

    private static $instance;
    private $connection;


    private function __construct()
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

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function __wakeup(){
// search //
    }
    private function __clone(){
// search //
    }


//    public function saveResetToken($userData, $token, $expireDate): void
//    {
//        $this->query('INSERT INTO `reset_tokens`(`user_id`, `token`, `expired_at`)VALUES (:user_id , :token , :expireDate) ',
//            [
//                'user_id' => $userData,
//                ':token' => $token,
//                ':expireDate' => $expireDate,
//            ]);
//
//
//    }
//
//    public function getToken($userId)
//    {
//        $tokenData = $this->query(
//            'SELECT `token` FROM `reset_tokens` WHERE `user_id` = :userId  AND expired_at >= NOW() AND used = 0 ORDER BY expired_at DESC LIMIT 1',
//            [
//                ':userId' => $userId,
//            ]
//        )->fetch(\PDO::FETCH_ASSOC);
//
//
//        return $tokenData;
//    }
//
//    public function markTokenAsUsed($userId, $token)
//    {
//
//        $this->query('UPDATE `reset_tokens` SET used = 1 WHERE user_id = :userId AND token = :token',
//            [
//                ':userId' => $userId,
//                ':token' => $token,
//            ]
//        );
//    }




}
