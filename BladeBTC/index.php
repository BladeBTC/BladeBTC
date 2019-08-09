<?php

require __DIR__ . '/bootstrap/app.php';

use BladeBTC\Helpers\WebHook;
use BladeBTC\Models\BotSetting;
use BladeBTC\Models\ErrorLogs;
use BladeBTC\WebHookHandler;
use Telegram\Bot\Api;

try {

    /**
     * Fatal error log
     */
    register_shutdown_function(function () {
        $error = error_get_last();

        if ($error !== null) {
            $errno = $error["type"];
            $errfile = $error["file"];
            $errline = $error["line"];
            $errstr = $error["message"];

            ErrorLogs::Log($errno, $errstr, $errline, 'BOT', $errfile);
        }
    });


    /**
     * Connect Telegram API
     */
    $telegram = new Api(BotSetting::getValueByName('app_id'));

    /**
     * Set WebHookURL
     */
    if (isset($_GET['setWebHookUrl']) && !empty($_GET['setWebHookUrl'])) {
        WebHook::set($telegram, $_GET['setWebHookUrl']);
    }

    /**
     * Remove WebHookURL
     */
    if (isset($_GET['removeWebHookUrl'])) {
        WebHook::remove($telegram);
    }

    /**
     * WebHookHandler
     */
    $webHook = new WebHookHandler($telegram);

} catch (Exception $e) {

    error_log($e->getMessage() . " on line " . $e->getLine() . " in file " . $e->getFile());
}
