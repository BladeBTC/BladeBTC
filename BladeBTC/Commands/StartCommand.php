<?php


namespace BladeBTC\Commands;

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
         * Display DEBUG Info
         */
        if (\getenv('DEBUG') == true) {
            $this->replyWithMessage([
                'text' => $this->getUpdate()->getMessage()
            ]);
        }


        /**
         * Keyboard
         */
        $keyboard = [
            ["REVENUE \xF0\x9F\x93\x88"],
            ["COMMUNITY \xF0\x9F\x8C\x8F"],
            ["SUPPORT \xF0\x9F\x92\xAC"],
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
            'text' => "Nice to see you <b>" . $this->getUpdate()->getMessage()->getFrom()->getFirstName() . "</b>\nTo explore me use controls below.",
            'reply_markup' => $reply_markup,
            'parse_mode' => 'HTML'
        ]);
    }
}