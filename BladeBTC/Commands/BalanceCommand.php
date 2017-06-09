<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Helpers;
use BladeBTC\Models\Users;
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
         * Chat data
         */
        $id = $this->update->getMessage()->getFrom()->getId();


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
         * Verify user
         */
        $user = new Users($id);
        if ($user->exist() == false) {

            $this->triggerCommand('start');

        } else {

            /**
             * Response
             */
            $this->replyWithMessage([
                'text' => "Your account balance:
<b>" . Helpers::btc($user->getBalance()) . "</b> BTC
Total invested:
<b>" . Helpers::btc($user->getInvested()) . "</b> BTC
Active investment:
<b>" . Helpers::btc($user->getActiveInvestment()) . "</b>/125 BTC
Total profit:
<b>" . Helpers::btc($user->getProfit()) . "</b> BTC
Total Commission:
<b>" . Helpers::btc($user->getCommission()) . "</b> BTC\n
Total Payout:
<b>" . Helpers::btc($user->getPayout()) . "</b> BTC\n
<b>Your investment:</b>
" . ($user->getActiveInvestment() == 0 ? "No active investment, start now with just 0.02 BTC" : Helpers::btc($user->getActiveInvestment())) . "
\nBase rate: <b>4% per day.</b>
You may start another investment by pressing the \"Invest\" button. Your balance will grow according to the base rate and your referrals.",
                'reply_markup' => $reply_markup,
                'parse_mode' => 'HTML'
            ]);
        }
    }
}