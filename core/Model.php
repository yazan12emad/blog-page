<?php

namespace app\core;

abstract class Model
{
    protected string $table;
    public function getDb(): DataBase
    {
        return DataBase::getInstance();
    }

    public function getSession(): Session
    {
        return Session::getInstance();

    }




}

