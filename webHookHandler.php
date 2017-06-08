<?php

require 'vendor/autoload.php';

use Telegram\Bot\Api;

/**
 * Connect Telegram API
 */
$telegram = new Api('384533803:AAE1pyxwEVQVZ_ayHc3glmoWZ4_GwtJCZK4');


/**
 * Populate commands list
 */
$telegram->addCommands([
    BladeBTC\Commands\StartCommand::class,
    BladeBTC\Commands\RevenueCommand::class
]);


/**
 * Handle commands
 */
$telegram->commandsHandler(true);


/**
 * Handle text command with unicode characters (Button)
 */
$message = $telegram->getWebhookUpdates()->getMessage();
if ($message !== null && $message->has('text')) {
    $command = "/" . strtolower(explode(" ", $message->getText())[0]);
    $telegram->getCommandBus()->handler($command, $telegram->getWebhookUpdates());
}
