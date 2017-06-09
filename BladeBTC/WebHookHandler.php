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
        if (preg_match('%start%', $update->getMessage()->getText())) {
            $telegram->getCommandBus()->execute('start', '', $update);
        }

        if (preg_match('%revenue%', $update->getMessage()->getText())) {
            mail("ylafontaine@addison-electronique.com", "test", "revenue");
            $telegram->getCommandBus()->execute('revenue', '', $update);
        }

        if (preg_match('%balance%', $update->getMessage()->getText())) {
            $telegram->getCommandBus()->execute('balance', '', $update);
        }

        if (preg_match('%invest%', $update->getMessage()->getText())) {
            $telegram->getCommandBus()->execute('invest', '', $update);
        }

        if (preg_match('%withdraw%', $update->getMessage()->getText())) {
            $telegram->getCommandBus()->execute('withdraw', '', $update);
        }

        if (preg_match('%reinvest%', $update->getMessage()->getText())) {
            $telegram->getCommandBus()->execute('reinvest', '', $update);
        }

        if (preg_match('%team%', $update->getMessage()->getText())) {
            $telegram->getCommandBus()->execute('team', '', $update);
        }

        if (preg_match('%back%', $update->getMessage()->getText())) {
            mail("ylafontaine@addison-electronique.com", "test", "revenue");

            $telegram->getCommandBus()->execute('back', '', $update);
        }
    }
}