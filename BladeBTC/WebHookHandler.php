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
    public function __construct(Api $telegram)
    {

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
        $updates = $telegram->getWebhookUpdates();
        $text = $updates->getMessage()->getText();


        if (preg_match('%start%', strtolower($text))) {
            $telegram->getCommandBus()->handler('start', $updates);
        }

        if (preg_match('%revenue%', strtolower($text))) {
            $telegram->getCommandBus()->handler('revenue', $updates);
        }

        if (preg_match('%balance%', strtolower($text))) {
            $telegram->getCommandBus()->handler('balance', $updates);
        }

        if (preg_match('%invest%', strtolower($text))) {
            $telegram->getCommandBus()->handler('invest', $updates);
        }

        if (preg_match('%withdraw%', strtolower($text))) {
            $telegram->getCommandBus()->handler('withdraw', $updates);
        }

        if (preg_match('%reinvest%', strtolower($text))) {
            $telegram->getCommandBus()->handler('reinvest', $updates);
        }

        if (preg_match('%team%', strtolower($text))) {
            $telegram->getCommandBus()->handler('team', $updates);
        }

        if (preg_match('%back%', strtolower($text))) {
            $telegram->getCommandBus()->handler('back', $updates);
        }
    }
}