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

        if (preg_match("/\bRevenue\b/i", $text)) {
            $telegram->getCommandBus()->handler('/revenue', $updates);
        }

        if (preg_match("/\bBalance\b/i", $text)) {
            $telegram->getCommandBus()->handler('/balance', $updates);
        }

        if (preg_match("/\bInvest\b/i", $text)) {
            $telegram->getCommandBus()->handler('/invest', $updates);
        }

        if (preg_match("/\bWithdraw\b/i", $text)) {
            $telegram->getCommandBus()->handler('/withdraw', $updates);
        }

        if (preg_match("/\bReinvest\b/i", $text)) {
            $telegram->getCommandBus()->handler('/reinvest', $updates);
        }

        if (preg_match("/\bTeam\b/i", $text)) {
            $telegram->getCommandBus()->handler('/team', $updates);
        }

        if (preg_match("/\bBack\b/i", $text)) {
            $telegram->getCommandBus()->handler('/back', $updates);
        }
    }
}