<?php

namespace BladeBTC\Helpers;

use PDO;

class Database
{
    public static function get()
    {

        /**
         * Credentials
         */
        $host = getenv("DB_HOST");
        $username = getenv("DB_USER");
        $password = getenv("DB_PASS");
        $bdd = getenv("DB_DB");
        $dsn = 'mysql:host=' . $host . ';dbname=' . $bdd;
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        /**
         * Connexion
         */
        return new PDO($dsn, $username, $password, $options);
    }
}