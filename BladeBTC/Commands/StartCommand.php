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
         * Chat data
         */
        $username = $this->update->getMessage()->getFrom()->getUsername();
        $first_name = $this->update->getMessage()->getFrom()->getFirstName();
        $last_name = $this->update->getMessage()->getFrom()->getLastName();
        $id = $this->update->getMessage()->getFrom()->getId();


        /**
         * Keyboard
         */
        $keyboard = [
            ["My balance \xF0\x9F\x92\xB0"],
            ["Invest \xF0\x9F\x92\xB5", "Withdraw \xE2\x8C\x9B"],
			["Reinvest \xE2\x86\xA9", "Help \xE2\x9D\x93"],
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
        $user = new Users($id);
        if ($user->exist() == false) {

            $user->create([
                "username" => isset($username) ? $username : "not set",
                "first_name" => isset($first_name) ? $first_name : "not set",
                "last_name" => isset($last_name) ? $last_name : "not set",
                "id" => isset($id) ? $id : "not set",
            ]);

            /**
             * Response
             */
            $this->replyWithMessage([
                'text' => "Welcome <b>" . $first_name . "</b>\nTo explore me use controls below. \xF0\x9F\x98\x84",
                'reply_markup' => $reply_markup,
                'parse_mode' => 'HTML'
            ]);

        } else {

            /**
             * Response
             */
            $this->replyWithMessage([
                'text' => "Nice to see you again <b>" . $first_name . "</b>\nTo explore me use controls below. \xF0\x9F\x98\x84",
                'reply_markup' => $reply_markup,
                'parse_mode' => 'HTML'
            ]);
        }
    }
}