<?php

namespace App\Repository;

use PDO;
use Framework\Database\Database;
use Framework\DbConfig\DbConfig;


abstract class AbstractRepository
{
    protected static ?PDO $pdo = null;
    protected static ?AbstractRepository $instance = null;

    public static function getInstance(): static
    {
        $config = DbConfig::getInstance();
        
        if (self::$pdo === null) {
            self::$pdo = (new Database($config))->getPDO();
        }

        // if (static::$instance === null) {
            static::$instance = new static(); //retourne une instance de la classe enfant
        // }

        return static::$instance;
    }

}