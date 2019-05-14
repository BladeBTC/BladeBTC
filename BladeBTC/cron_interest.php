<?php

require __DIR__ . '/bootstrap/app.php';

use BladeBTC\Models\Investment;

try {

    /**
     * Load .env file
     */
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();

    /*
     * ===========================================  GIVE INTEREST ==========================================
     * Interest must be apply before handle deposit.
     */

    Investment::giveInterest();

} catch (Exception $e) {

    if (getenv("DEBUG") == 1) {
        mail(getenv("MAIL"), "BOT ERROR", $e->getMessage() . "\n" . $e->getFile() . "[" . $e->getLine() . "]");
    }
}
