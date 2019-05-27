<?php

namespace BladeBTC;

use BladeBTC\Models\ErrorLogs;
use Exception;
use Telegram\Bot\Api;

/**
 * Class WebHookHandler
 *
 * @package BladeBTC
 */
class WebHookHandler
{

    /**
     * WebHookHandler constructor.
     *
     * @param Api $telegram
     *
     * @throws Exception
     */
    public function __construct(Api $telegram)
    {
        try {

            /**
             * Populate commands list
             */
            $telegram->addCommands([
                Commands\StartCommand::class,
                Commands\WalletBalanceCommand::class,
                Commands\BalanceCommand::class,
                Commands\InvestCommand::class,
                Commands\WithdrawCommand::class,
                Commands\ReinvestCommand::class,
                Commands\BackCommand::class,
                Commands\ErrorCommand::class,
                Commands\UpdateWalletCommand::class,
                Commands\OutCommand::class,
                Commands\ReferralCommand::class,
                Commands\InfoCommand::class,
            ]);


            /**
             * Get updates data
             */
            $updates = $telegram->getWebhookUpdates();


            /**
             * Count updates to avoid error if user go to the application index by itself.
             */
            if (count($updates) > 0) {


                /**
                 * Log message in log files
                 */
                if (getenv('DEBUG') == 1) {
                    error_log("update :" . $updates->getMessage(), 0);
                }


                /**
                 * Get current updates message
                 */
                $message = $updates->getMessage();


                /**
                 * Skip empty message
                 */
                if (empty($message)) {
                    return 1;
                }


                /**
                 * Get current updates message
                 */
                $text = $message->getText();


                /**
                 * Handle command
                 */
                if (preg_match("/\bBalance\b/i", $text)) {
                    $telegram->getCommandBus()->handler('/balance', $updates);
                }
                elseif (preg_match("/\bInvest\b/i", $text)) {
                    $telegram->getCommandBus()->handler('/invest', $updates);
                }
                elseif (preg_match("/\bWithdraw\b/i", $text)) {
                    $telegram->getCommandBus()->handler('/withdraw', $updates);
                }
                elseif (preg_match("/\bReinvest\b/i", $text)) {
                    $telegram->getCommandBus()->handler('/reinvest', $updates);
                }
                elseif (preg_match("/\bBack\b/i", $text)) {
                    $telegram->getCommandBus()->handler('/back', $updates);
                }
                elseif (preg_match("/\bTeam\b/i", $text)) {
                    $telegram->getCommandBus()->handler('/referral', $updates);
                }
                elseif (preg_match("/\bHelp\b/i", $text)) {
                    $telegram->getCommandBus()->handler('/info', $updates);
                }
                elseif (preg_match("/\/out/", $text)) {
                    $telegram->getCommandBus()->handler('/out', $updates);
                }
                elseif (preg_match("/\/set/", $text)) {
                    $telegram->getCommandBus()->handler('/update_wallet', $updates);
                }
                elseif (preg_match("/\/gwb/", $text)) {
                    $telegram->getCommandBus()->handler('/gwb', $updates);
                }
                elseif (preg_match("/\/start/", $text)) {
                    $telegram->getCommandBus()->handler($text, $updates);
                }
                else {
                    $telegram->getCommandBus()->handler('/error', $updates);
                }
            }

        } catch (Exception $e) {

            try {

                ErrorLogs::Log($e->getCode(), $e->getMessage(), $e->getLine(), 'BOT', $e->getFile());

            } catch (Exception $q) {

                error_log($q->getMessage() . " on line " . $q->getLine() . " in file " . $q->getFile());
            }
        }

        return 1;
    }
}