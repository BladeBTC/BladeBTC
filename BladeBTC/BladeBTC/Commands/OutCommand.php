<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Helpers\Wallet;
use BladeBTC\Models\InvestmentPlan;
use BladeBTC\Models\Users;
use Exception;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class OutCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "out";

    /**
     * @var string Command Description
     */
    protected $description = "Withdraw";

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

            $out_amount = trim(substr($this->update->getMessage()->getText(), 4));


            try {


                /**
                 * Check if out amount is empty
                 */
                if (empty($out_amount)){

                    $this->replyWithMessage([
                        'text' => "You need to enter an amount after the /out command. The amount received by our server was empty.",
                        'reply_markup' => $reply_markup,
                        'parse_mode' => 'HTML',
                    ]);

                }


                /**
                 * Validate payout amount requested
                 */
                elseif (!is_numeric($out_amount) || $out_amount < InvestmentPlan::getValueByName("minimum_payout")) {

                    $this->replyWithMessage([
                        'text' => "You need to payout at least " . InvestmentPlan::getValueByName("minimum_payout") . " BTC. Also the amount need to be numeric.",
                        'reply_markup' => $reply_markup,
                        'parse_mode' => 'HTML',
                    ]);
                }


                /**
                 * Validate account balance
                 */
                elseif ($user->getBalance() < $out_amount) {

                    $this->replyWithMessage([
                        'text' => "I'm sorry to tell you this but your account have not enough balance.",
                        'reply_markup' => $reply_markup,
                        'parse_mode' => 'HTML',
                    ]);
                }


                /**
                 * Withdraw
                 */
                else {

                    $transaction = Wallet::makeOutgoingPayment($user->getWalletAddress(), Btc::BtcToSatoshi($out_amount));

                    if (!empty($transaction) && empty($transaction->error)) {


                        /**
                         * Update user balance
                         */
                        $user->updateBalance($out_amount, $transaction);


                        /**
                         * Response
                         */
                        $this->replyWithMessage([
                            'text' => "Message :\n<b>" . $transaction->message . "</b>\n" . "Transaction ID:\n<b>" . $transaction->tx_hash . "</b>\n" . "Notice:\n<b>" . $transaction->notice . "</b>",
                            'reply_markup' => $reply_markup,
                            'parse_mode' => 'HTML',
                        ]);

                    }
                    else {

                        /**
                         * Response
                         */
                        $this->replyWithMessage([
                            'text' => "An error occurred while withdrawing your BTC.\n<b>[Error] " . $transaction->error . "</b>. \xF0\x9F\x98\x96",
                            'reply_markup' => $reply_markup,
                            'parse_mode' => 'HTML',
                        ]);
                    }
                }
            } catch (Exception $e){

                $this->replyWithMessage([
                    'text'         => "An error occurred during withdraw process.\n" . $e->getMessage() . ". \xF0\x9F\x98\x96",
                    'reply_markup' => $reply_markup,
                    'parse_mode'   => 'HTML'
                ]);
            }
        }
    }
}