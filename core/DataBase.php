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
        $this->connection = new \PDO(
            $dsnString,
            $DataBaseKeys['user'] ?? 'root',
            $DataBaseKeys['pass'] ?? ''
        );

        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function query($query, $params = []): false|\PDOStatement
    {
        try {
            $stmt = $this->connection->prepare($query);

            $stmt->execute($params); // pass parameters!

            return $stmt;

        } catch (\PDOException $e) {

            echo $e->getMessage();

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
