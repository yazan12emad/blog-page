<?php

namespace app\core\DataBase;

interface DataBaseConnection
{
    public function getConnection(): \PDO ;

}