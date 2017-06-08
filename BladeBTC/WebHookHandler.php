<?php

namespace BladeBTC;

use Telegram\Bot\Api;

/**
 * Class WebHookHandler
 * @package BladeBTC
 */
class WebHookHandler
{

    /**
     * WebHookHandler constructor.
     */
    public function __construct()
    {
        /**
         * Connect Telegram API
         */
        $telegram = new Api('384533803:AAE1pyxwEVQVZ_ayHc3glmoWZ4_GwtJCZK4');


        /**
         * Populate commands list
         */
        $telegram->addCommands([
            Commands\StartCommand::class,
            Commands\RevenueCommand::class
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

    }
}