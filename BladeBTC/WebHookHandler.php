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

            mail("ylafontaine@addison-electronique.com", strtolower($text), "message");

            $telegram->getCommandBus()->handler('/start', $updates);
        }

        if (preg_match('%revenue%', strtolower($text))) {

            mail("ylafontaine@addison-electronique.com", strtolower($text), "message");


            $telegram->getCommandBus()->handler('/revenue', $updates);
        }

        if (preg_match('%balance%', strtolower($text))) {
            mail("ylafontaine@addison-electronique.com", strtolower($text), "message");

            $telegram->getCommandBus()->handler('/balance', $updates);
        }

        if (preg_match('%invest%', strtolower($text))) {
            mail("ylafontaine@addison-electronique.com", strtolower($text), "message");

            $telegram->getCommandBus()->handler('/invest', $updates);
        }

        if (preg_match('%withdraw%', strtolower($text))) {
            mail("ylafontaine@addison-electronique.com", strtolower($text), "message");

            $telegram->getCommandBus()->handler('/withdraw', $updates);
        }

        if (preg_match('%reinvest%', strtolower($text))) {
            mail("ylafontaine@addison-electronique.com", strtolower($text), "message");

            $telegram->getCommandBus()->handler('/reinvest', $updates);
        }

        if (preg_match('%team%', strtolower($text))) {
            mail("ylafontaine@addison-electronique.com", strtolower($text), "message");

            $telegram->getCommandBus()->handler('/team', $updates);
        }

        if (preg_match('%back%', strtolower($text))) {
            mail("ylafontaine@addison-electronique.com", strtolower($text), "message");

            $telegram->getCommandBus()->handler('/back', $updates);
        }
    }
}