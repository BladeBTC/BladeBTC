<?php


namespace BladeBTC\Commands;

use BladeBTC\Models\Users;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "start";

    /**
     * @var string Command Description
     */
    protected $description = "Start bot";

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
         * Add user to our database
         */
        $user = new Users($this->update->getMessage()->getFrom()->getId());
        if ($user->exist() == false) {

            throw new \Exception($this->update->getMessage());

            $result = $user->create([
                "username" => $this->update->getMessage()->getFrom()->getUsername(),
                "first_name" => $this->update->getMessage()->getFrom()->getFirstName(),
                "last_name" => $this->update->getMessage()->getFrom()->getLastName(),
                "id" => $this->update->getMessage()->getFrom()->getId(),
            ]);

            if ($result == true) {

                /**
                 * Response
                 */
                $this->replyWithMessage([
                    'text' => "Welcome <b>" . $this->getUpdate()->getMessage()->getFrom()->getFirstName() . "</b>\nTo explore me use controls below. \xF0\x9F\x98\x84",
                    'reply_markup' => $reply_markup,
                    'parse_mode' => 'HTML'
                ]);
            } //User creation error
            else {

                /**
                 * Response
                 */
                $this->replyWithMessage([
                    'text' => "An error occured while creating your account.\nWe're sorry about this situation. \xF0\x9F\x98\x96",
                ]);
            }
        } else {

            /**
             * Response
             */
            $this->replyWithMessage([
                'text' => "Nice to see you again <b>" . $this->getUpdate()->getMessage()->getFrom()->getFirstName() . "</b>\nTo explore me use controls below. \xF0\x9F\x98\x84",
                'reply_markup' => $reply_markup,
                'parse_mode' => 'HTML'
            ]);
        }
    }
}