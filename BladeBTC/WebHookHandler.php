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
        $update = $telegram->commandsHandler(true);


        /**
         * Handle text command with unicode characters (Button)
         */
        if (preg_match('%start%', strtolower($update->getMessage()->getText()))) {
            $telegram->getCommandBus()->handler('start', $update);
        }

        if (preg_match('%revenue%', strtolower($update->getMessage()->getText()))) {
            $telegram->getCommandBus()->handler('revenue', $update);
        }

        if (preg_match('%balance%', strtolower($update->getMessage()->getText()))) {
            $telegram->getCommandBus()->handler('balance', $update);
        }

        if (preg_match('%invest%', strtolower($update->getMessage()->getText()))) {
            $telegram->getCommandBus()->handler('invest', $update);
        }

        if (preg_match('%withdraw%', strtolower($update->getMessage()->getText()))) {
            $telegram->getCommandBus()->handler('withdraw', $update);
        }

        if (preg_match('%reinvest%', strtolower($update->getMessage()->getText()))) {
            $telegram->getCommandBus()->handler('reinvest', $update);
        }

        if (preg_match('%team%', strtolower($update->getMessage()->getText()))) {
            $telegram->getCommandBus()->handler('team', $update);
        }

        if (preg_match('%back%', strtolower($update->getMessage()->getText()))) {
            $telegram->getCommandBus()->handler('back', $update);
        }
    }
}