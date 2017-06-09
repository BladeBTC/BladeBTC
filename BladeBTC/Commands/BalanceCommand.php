<?php


namespace BladeBTC\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class BalanceCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "balance";

    /**
     * @var string Command Description
     */
    protected $description = "Display account balance.";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {

        /**
         * Keyboard
         */
        $keyboard = [
            ["My balance \xF0\x9F\x92\xB0"],
            ["Invest \xF0\x9F\x92\xB5", "Withdraw \xE2\x8C\x9B"],
            ["Reinvest \xE2\x86\xA9", "My team \xF0\x9F\x91\xA8"],
            ["Back to main menu \xE2\xAC\x85"],

        ];

        $reply_markup = $this->telegram->replyKeyboardMarkup([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ]);


        /**
         * Display Typing...
         */
        $this->replyWithChatAction(['action' => Actions::TYPING]);


        /**
         * Response
         */
        $this->replyWithMessage([
            'text' => " Your account balance:\n
                        0.00000000 BTC\n
                        Total invested:\n
                        0.00000000 BTC\n
                        Active investment:\n
                        0.00000000/125 BTC\n
                        Total profit:\n
                        0.00000000 BTC\n\n
                        Total Commission:\n
                        0.00000000 BTC\n
                        Total Payout:\n
                        0.00000000 BTC\n\n
                        Your investment:\n
                        No active investment, start now with just 0.02 BTC\n\n
                        Base rate: 4% per day.\n
                        You may start another investment by pressing the \"Invest\" button. Your balance will grow according to the base rate and your referrals.",
            'reply_markup' => $reply_markup,
            'parse_mode' => 'HTML'
        ]);
    }
}