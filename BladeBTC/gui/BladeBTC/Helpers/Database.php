<?php

namespace BladeBTC\GUI\Helpers;

use DebugBar\DataCollector\PDO\PDOCollector;
use DebugBar\DataCollector\PDO\TraceablePDO;
use Exception;
use PDO;

class Database
{

    /**
     * @var array - PDO Instances
     */
    protected static $instances = [];


    /**
     * Early initialization of this class
     * Required to register a PDO collector on all
     * database connection for the debug bar.
     */
    public static function init()
    {
        try {
            $instance_intranet = self::get();

            $pdoCollector = new PDOCollector();

            $pdoCollector->addConnection($instance_intranet, 'BladeBTC');

            Debugbar::registerPDOCollector($pdoCollector);
        } catch (Exception $e) {
            Toast::error($e->getMessage());
        }
    }


    /**
     * Get main database connection
     *
     * @return TraceablePDO|mixed
     */
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
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];


        /**
         * Database ID
         */
        $id = "$dsn.$username.$username.$password";


        /**
         * Check if database instance exist
         */
        if (isset(self::$instances[$id])) {
            return self::$instances[$id];
        }


        /**
         * Build or Get instance
         */
        $instance = new TraceablePDO(new PDO($dsn, $username, $password, $options));

        self::$instances[$id] = $instance;

        return $instance;
    }

    /**
     * Simple way to do transaction
     *
     * @param array $statements - Statements
     *
     * @throws Exception
     */
    public static function transaction(TraceablePDO $db, Array $statements)
    {
        try {

            $db->beginTransaction();

            foreach ($statements as $statement) {
                $db->exec($statement);
            }

            $db->commit();

        } catch (Exception $e) {

            $db->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Get next auto increment ID of a table.
     *
     * @param $table_name
     *
     * @return int $id
     */
    public static function getNextAutoIncrementId($table_name)
    {
        $db = self::get();

        $id = $db->query("SHOW TABLE STATUS LIKE '$table_name'")->fetchObject()->Auto_increment;

        return $id;
    }

    /**
     * Validate if field is unique
     *
     * @param       $table      - Table name
     * @param       $field      - Field name
     * @param       $value      - Value
     * @param array $exclude_id - Row Id to exclude
     *
     * @return bool
     */
    public static function fieldIsUnique($table, $field, $value, $exclude_id = [])
    {
        $db = self::get();

        if (count($exclude_id) > 0) {
            $condition = implode(" AND id = ", $exclude_id);
            $count = $db->query("SELECT COUNT(*) AS C FROM $table WHERE $field='$value' AND id != $condition")->fetchObject()->C;
        }
        else {
            $count = $db->query("SELECT COUNT(*) AS C FROM $table WHERE $field='$value'")->fetchObject()->C;
        }

        if ($count > 0) {
            return false;
        }
        else {
            return true;
        }
    }
}


