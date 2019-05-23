<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\AddressValidator;
use BladeBTC\Helpers\Btc;
use BladeBTC\Models\Users;
use Exception;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class UpdateWalletCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "update_wallet";

    /**
     * @var string Command Description
     */
    protected $description = "Update wallet address";

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
             * Get wallet address from message
             */
            $wallet_address = trim(substr($this->update->getMessage()->getText(), 4));

            try {

                /**
                 * Validate if address is empty
                 */
                if (empty($wallet_address)) {

                    $this->replyWithMessage([
                        'text' => "The wallet address received from our server was empty please check your command. \xF0\x9F\x98\x96",
                        'reply_markup' => $reply_markup,
                        'parse_mode' => 'HTML',
                    ]);

                }


                /**
                 * Validate address validity
                 */
                elseif (!AddressValidator::isValid($wallet_address)) {

                    $this->replyWithMessage([
                        'text' => "The wallet address (<b>$wallet_address</b>) is not recognized as a valid bitcoin address.\nPlease rerun the command with a valid address. \xF0\x9F\x98\x96",
                        'reply_markup' => $reply_markup,
                        'parse_mode' => 'HTML',
                    ]);
                }


                /**
                 * Update account
                 */
                else {


                    /**
                     * Store investment_address
                     */
                    $user->setWalletAddress($wallet_address);


                    /**
                     * Response
                     */
                    $this->replyWithMessage([
                        'text' => "Your wallet address (<b>$wallet_address</b>) is successfully set in your account.
To payout now please press the withdraw button again.",
                        'reply_markup' => $reply_markup,
                        'parse_mode' => 'HTML',
                    ]);
                }

            } catch (Exception $e) {
                $this->replyWithMessage([
                    'text' => "An error occurred while saving your wallet address. Error: " . $e->getMessage() . "\nPlease contact support. \xF0\x9F\x98\x96",
                    'reply_markup' => $reply_markup,
                    'parse_mode' => 'HTML',
                ]);
            }
        }
    }
}