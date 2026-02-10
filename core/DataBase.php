<?php


namespace app\core;

class DataBase
{

    private static $instance;
    private \PDO $connection;


    private function __construct()
    {
        $configKeys = require __DIR__ . '/../keys.php';
        $DataBaseKeys = $configKeys['DataBase'];
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
    private function __clone() {}
    public function __wakeup() { throw new \Exception("Cannot unserialize singleton"); }


    public function query($query, $params = []): false|\PDOStatement
    {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params); // pass parameters
            return $stmt;

        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }

    }


//    public static function make(string $driver): MySQLConnection
//    {
//        return match ($driver) {
//            'mysql' => new MySQLConnection(),
//            default => throw new Exception("Unsupported database driver"),
//        };
//    }

    public static function getInstance(): DataBase
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }



}
