<?php


namespace BladeBTC\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class BalanceCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "balance";

    /**
     * @var string Command Description
     */
    protected $description = "Display account balance.";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {

        mail("ylafontaine@addison-electronique.com", "allo", "test1");

        /**
         * Keyboard
         */
        $keyboard = [
            ["My balance \xF0\x9F\x92\xB0"],
            ["Invest \xF0\x9F\x92\xB5", "Withdraw \xE2\x8C\x9B"],
            ["Reinvest \xE2\x86\xA9", "My team \xF0\x9F\x91\xA8"],
            ["Back to main menu \xE2\xAC\x85"],

        ];


        mail("ylafontaine@addison-electronique.com", "allo", "test2");


        $reply_markup = $this->telegram->replyKeyboardMarkup([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ]);

        mail("ylafontaine@addison-electronique.com", "allo", "test3");


        /**
         * Display Typing...
         */
        $this->replyWithChatAction(['action' => Actions::TYPING]);


        mail("ylafontaine@addison-electronique.com", "allo", "test4");

        /**
         * Response
         */
        $this->replyWithMessage([
            'text' => "Balance menu!",
            'reply_markup' => $reply_markup,
            'parse_mode' => 'HTML'
        ]);


        mail("ylafontaine@addison-electronique.com", "allo", "test5");

    }
}