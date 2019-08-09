<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Models\InvestmentPlan;
use BladeBTC\Models\Users;
use Exception;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class ReinvestCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "reinvest";

    /**
     * @var string Command Description
     */
    protected $description = "Load reinvest menu.";

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
             * Check if balance is lower than the minimum reinvest
             */
            if ($user->getBalance() < InvestmentPlan::getValueByName("minimum_reinvest")) {


                $this->replyWithMessage([
                    'text' => "Sorry to tell you that, but your balance is not high enough for that!\n<b>Min: " . InvestmentPlan::getValueByName("minimum_reinvest") . " BTC</b>",
                    'reply_markup' => $reply_markup,
                    'parse_mode' => 'HTML',
                ]);

            }
            else {

                try {


                    /**
                     * Reinvest balance
                     */
                    $user->Reinvest();
                    $user->Refresh();


                    /**
                     * Response
                     */
                    $this->replyWithMessage([
                        'text' => "Congratulation your balance has been properly invested!",
                        'reply_markup' => $reply_markup,
                        'parse_mode' => 'HTML',
                    ]);


                    /**
                     * Show new balance
                     */
                    $this->triggerCommand("balance");

                } catch (Exception $e) {

                    $this->replyWithMessage([
                        'text' => "An error occurred while generating your payment address.\n" . $e->getMessage() . ". \xF0\x9F\x98\x96",
                        'reply_markup' => $reply_markup,
                        'parse_mode' => 'HTML'
                    ]);
                }
            }
        }
    }
}