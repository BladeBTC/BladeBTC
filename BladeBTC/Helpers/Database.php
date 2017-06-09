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
        $host = getenv('HOST');
        $username = getenv("USER");
        $password = getenv("PASS");
        $bdd = getenv("BDD");
        $dsn = 'mysql:host=' . $host . ';dbname=' . $bdd;
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        /**
         * Connexion
         */
        return new PDO($dsn, $username, $password, $options);
    }
}