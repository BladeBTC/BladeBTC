<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', getenv("DEBUG"));

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use BladeBTC\Helpers\WebHook;
use BladeBTC\WebHookHandler;
use Telegram\Bot\Api;

try {

    /**
     * Load .env file
     */
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();

    /**
     * Connect Telegram API
     */
    $telegram = new Api(getenv('APP_ID'));


    /**
     * Set WebHookURL
     */
    if (isset($_GET['setWebHookUrl']) && !empty($_GET['setWebHookUrl'])) {
        WebHook::set($telegram, $_GET['setWebHookUrl']);
    }


    /**
     * WebHookHandler
     */
    $webHook = new WebHookHandler($telegram);

} catch (Exception $e) {

    if (getenv("DEBUG") == 1) {
        mail(getenv("MAIL"), "BOT ERROR", $e->getMessage() . "\n" . $e->getFile() . "[" . $e->getLine() . "]");
    }
}


