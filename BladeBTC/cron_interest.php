<?php

require __DIR__ . '/bootstrap/app.php';

use BladeBTC\Models\ErrorLogs;
use BladeBTC\Models\Investment;

try {

    /**
     * Load .env file
     */
    $env = new Dotenv\Dotenv(__DIR__);
    $env->load();

    /**
     * Delete contract that end date is over.
     */
    Investment::endContract();

    /**
     * Apply interest over active contract.
     */
    Investment::giveInterest();

} catch (Exception $e) {

    try {

        ErrorLogs::Log($e->getCode(), $e->getMessage(), $e->getLine(), 'CRON INTEREST', $e->getFile());
        error_log($e->getMessage() . " on line " . $e->getLine() . " in file " . $e->getFile());

    } catch (Exception $q) {

        error_log($q->getMessage() . " on line " . $q->getLine() . " in file " . $q->getFile());
    }
}
