<?php


namespace BladeBTC\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Api;
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
    protected $description = "Invest BTC to your account";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        //Keyboard
        $keyboard = [
            ["BTC 0.0000000000"],
            ["Option 1", "Option 2"],
            ["Option 4", "Option 4"],
            ["Back"],
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
            //  'text' => "Nice to see you <b>" . $this->getUpdate()->getMessage()->getFrom()->getFirstName() . "</b>\nTo explore me use controls below.",
            'reply_markup' => $reply_markup,
            'parse_mode' => 'HTML'
        ]);
    }
}