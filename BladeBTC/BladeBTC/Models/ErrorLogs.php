<?php

namespace BladeBTC\Models;

use BladeBTC\Helpers\Database;
use Exception;

/**
 * Class ErrorLogs
 *
 * @package BladeBTC\Models
 */
class ErrorLogs
{

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
}