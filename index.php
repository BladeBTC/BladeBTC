<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use BladeBTC\Helpers\WebHook;
use BladeBTC\WebHookHandler;
use Telegram\Bot\Api;


/**
 * Connect Telegram API
 */
$telegram = new Api(getenv('API_KEY'));


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