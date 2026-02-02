<?php

namespace app\core;

use app\core\DataBase\DataBaseConnection;
use PDO;

class MySQLConnection implements DataBaseConnection

{
    private  ?PDO $instance = null;

    private function __construct() {}
    public function getConnection(): \PDO
    {
            if ($this->instance === null) {
                $configKeys = require('keys.php');
                $DataBaseKeys = $configKeys['DataBase'];
                $dsnString = "mysql:host={$DataBaseKeys['host']};dbname={$DataBaseKeys['dbname']};charset=utf8mb4";
                return  new \PDO(
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
            return $this->instance;
}
}