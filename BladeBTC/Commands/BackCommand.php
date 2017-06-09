<?php


namespace BladeBTC\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class BackCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "back";

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
         * Display Typing...
         */
        $this->replyWithChatAction(['action' => Actions::TYPING]);


        mail("ylafontaine@addison-electronique.com", "test", "twsewewest");

        /**
         * Call /start
         */
        $this->triggerCommand('start');
    }
}