<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Models\InvestmentPlan;
use BladeBTC\Models\Users;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class WithdrawCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "withdraw";

    /**
     * @var string Command Description
     */
    protected $description = "Load withdraw menu";

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
         * Display Typing...
         */
        $this->replyWithChatAction([ 'action' => Actions::TYPING ]);

        /**
         * Verify user
         */
        $user = new Users($id);
        if ($user->exist() == false) {

            $this->triggerCommand('start');

        }
        else {

            /**
             * Keyboard
             */
            $keyboard = [
                [ "My balance " . Btc::Format($user->getBalance()) . " \xF0\x9F\x92\xB0" ],
                [ "Invest \xF0\x9F\x92\xB5", "Withdraw \xE2\x8C\x9B" ],
                [ "Reinvest \xE2\x86\xA9", "Help \xE2\x9D\x93" ],
                [ "My Team \xF0\x9F\x91\xAB" ],
            ];

            $reply_markup = $this->telegram->replyKeyboardMarkup([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
            ]);

            /**
             * Verify user wallet address
             */
            if (is_null($user->getWalletAddress())) {

                $this->replyWithMessage([
                    'text' => "Your withdraw address is <b>not set</b>\n
Use command /set ADDRESS to update your account.\n
For example:\n
/set 19a7txmi1fPaNaTiBec6uopVHJ4BxpxZEE\n
It's possible to change your withdraw address at any time using this command.",
                    'reply_markup' => $reply_markup,
                    'parse_mode' => 'HTML',
                ]);

            }
            else {

                $this->replyWithMessage([
                    'text' => "Your withdraw address is :\n
<b>" . $user->getWalletAddress() . "</b>\n
Use command /set WALLET to update your account, for example: /set 19a7txmi1fPaNaTiBec6uopVHJ4BxpxZEE\n
Use command /out AMOUNT, for example: /out 1.2
Specified amount will be delivered to your address ASAP.
(Usually during one or two hours - min: " . InvestmentPlan::getValueByName("minimum_payout") . "BTC).",
                    'reply_markup' => $reply_markup,
                    'parse_mode' => 'HTML',
                ]);

            }
        }
    }
}