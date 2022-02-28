<?php

class DB extends PDO
{
    private static $instance = null;

    private function __construct($db)
    {
        $dsn = 'mysql:host=' . $db['server'] . ';
        dbname=' . $db['db'] . ';
        charset=utf8mb4;';

        parent::__construct($dsn,$db['user'],$db['password']);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
    }

    public static function getInstance()
    {
        if(self::$instance==null){
            self::$instance = new self(App::config('db'));
        }
        return self::$instance;
    }
}