<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use BladeBTC\Helpers\WebHook;
use BladeBTC\WebHookHandler;
use Telegram\Bot\Api;

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


/**
 * Display DEBUG Info
 */
if (getenv('DEBUG')) {
    $telegram->sendMessage([
        'test' => $telegram->getWebhookUpdates()->getMessage()
    ]);
}