<?php

namespace BladeBTC\GUI\Models;

use BladeBTC\GUI\Helpers\Database;
use BladeBTC\GUI\Helpers\Session;
use DebugBar\DataCollector\PDO\TraceablePDOStatement;
use Exception;

/**
 * Class ErrorLogs
 *
 * @package BladeBTC\Models
 */
class ErrorLogs
{

    /**
     * Get all error log
     *
     * @param bool $include_deleted
     *
     * @return bool|TraceablePDOStatement
     */
    public static function getAll($include_deleted = false)
    {
        $db = Database::get();

        if ($include_deleted) {
            $errors = $db->query("SELECT * FROM error_logs");
        }
        else {
            $errors = $db->query("SELECT * FROM error_logs WHERE deleted = 0");
        }

        return $errors;
    }

    /**
     * Log an error
     *
     * @param $error_number
     * @param $error
     * @param $line
     *
     * @param $source
     * @param $file
     *
     * @throws Exception
     */
    public static function Log($error_number, $error, $line, $source, $file)
    {

        $db = Database::get();

        try {

            $db->beginTransaction();
            $db->query("INSERT
                                    INTO
                                        `error_logs`(
                                            `error_number`,
                                            `error`,
                                            `file`,
                                            `source`,
                                            `line`
                                        )
                                    VALUES(
                                        " . $db->quote($error_number) . ",
                                        " . $db->quote($error) . ",
                                        " . $db->quote($file) . ",
                                        " . $db->quote($source) . ",
                                        " . $db->quote($line) . "
                                    )");
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Delete an error line
     *
     * @param $error_line
     *
     * @throws Exception
     */
    public static function delete($error_line)
    {

        $db = Database::get();

        $query = "	UPDATE 
						error_logs 
					SET 
						deleted = 1,
					    deleted_account_id = :deleted_account_id,
					    deleted_date = NOW()
					WHERE 
						id = :id";

        $sth = $db->prepare($query);

        $sth->execute([
            "id" => $error_line,
            "deleted_account_id" => Session::get("account_id"),
        ]);
    }

    /**
     * Clear all error lines
     *
     * @throws Exception
     */
    public static function clear()
    {

        $db = Database::get();

        $query = "	UPDATE 
						error_logs 
					SET 
						deleted = 1,
					    deleted_account_id = :deleted_account_id,
					    deleted_date = NOW()
					WHERE 
						deleted = 0";

        $sth = $db->prepare($query);

        $sth->execute([
            "deleted_account_id" => Session::get("account_id"),
        ]);
    }
}