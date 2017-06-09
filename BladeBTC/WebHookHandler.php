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
            Commands\RevenueCommand::class,
            Commands\BalanceCommand::class,
            Commands\InvestCommand::class,
            Commands\WithdrawCommand::class,
            Commands\ReinvestCommand::class,
            Commands\TeamCommand::class,
            Commands\BackCommand::class,
        ]);


        /**
         * Handle commands
         */
        $telegram->commandsHandler(true);


        /**
         * Handle text command (button)
         */
        $updates = $telegram->getWebhookUpdates();
        $text = $updates->getMessage()->getText();

        if (preg_match("\bstart\b/i", $text)) {
            $telegram->getCommandBus()->handler('/start', $updates);
        }

        if (preg_match('\brevenue\b/i', $text)) {
            $telegram->getCommandBus()->handler('/revenue', $updates);
        }

        if (preg_match('\bbalance\b/i', $text)) {
            $telegram->getCommandBus()->handler('/balance', $updates);
        }

        if (preg_match("\binvest\b/i", $text)) {
            mail("ylafontaine@addison-electronique.com", "test", "test");
            $telegram->getCommandBus()->handler('/invest', $updates);
        }

        if (preg_match('\bwithdraw\b/i', $text)) {
            $telegram->getCommandBus()->handler('/withdraw', $updates);
        }

        if (preg_match('\breinvest\b/i', $text)) {
            $telegram->getCommandBus()->handler('/reinvest', $updates);
        }

        if (preg_match('\bteam\b/i', $text)) {
            $telegram->getCommandBus()->handler('/team', $updates);
        }

        if (preg_match('\bback\b/i', $text)) {
            $telegram->getCommandBus()->handler('/back', $updates);
        }
    }
}