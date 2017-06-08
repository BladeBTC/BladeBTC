<?php


namespace BladeBTC\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;

class BalanceCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "my";

    /**
     * @var string Command Description
     */
    protected $description = "Load BTC menu";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {

        //Keyboard
        $keyboard = [
            ["My balance 0.0000000"],
            ["Invest", "Newbie"],
            ["Reinvest", "My team"],
        ];
        $telegram = new Api('384533803:AAE1pyxwEVQVZ_ayHc3glmoWZ4_GwtJCZK4');
        $reply_markup = $telegram->replyKeyboardMarkup([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        // This will update the chat status to typing...
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        //Welcome message.
        $this->replyWithMessage([
            'text' => "Revenue menu!",
            'reply_markup' => $reply_markup,
            'parse_mode' => 'HTML'
        ]);
    }
}