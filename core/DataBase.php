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

        $dsnString = "mysql:host={$DataBaseKeys['host']};dbname={$DataBaseKeys['dbname']};charset=utf8mb4";
        $this->connection = new \PDO(
            $dsnString,
            $DataBaseKeys['user'] ?? 'root',
            $DataBaseKeys['pass'] ?? '',
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,  //If any SQL error occurs it force PDO to throw an Exception
                \PDO::ATTR_EMULATE_PREPARES => false, // It prevents the SQL from take prepared statement emulated (Disable Emulated Prepares)
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC //It ensures the DB to return an Associative array
            ]
        );

    }

    public function query($query, $params = []): false|\PDOStatement
    {
        try {
            $stmt = $this->connection->prepare($query);

            $stmt->execute($params); // pass parameters!

            return $stmt;

        } catch (\PDOException $e) {

            error_log($e->getMessage());
            return false;
        }

    }

    public static function getInstance(): DataBase
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


}
