<?php


namespace BladeBTC\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class RevenueCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "revenue";

    /**
     * @var string Command Description
     */
    protected $description = "Load main menu";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {

        /**
         * Keyboard
         */
        $keyboard = [
            ["My balance 0.0000000"],
            ["Invest", "Newbie"],
            ["Reinvest", "My team"],
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
            'text' => "Revenue menu!",
            'reply_markup' => $reply_markup,
            'parse_mode' => 'HTML'
        ]);
    }
}