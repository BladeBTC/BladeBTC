<?php


namespace BladeBTC\Commands;

use BladeBTC\Models\Users;
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
		 * Keyboard
		 */
		$keyboard = [
			["My balance \xF0\x9F\x92\xB0"],
			["Invest \xF0\x9F\x92\xB5", "Withdraw \xE2\x8C\x9B"],
			["Reinvest \xE2\x86\xA9", "My team \xF0\x9F\x91\xA8"],
		];

		$reply_markup = $this->telegram->replyKeyboardMarkup([
			'keyboard'          => $keyboard,
			'resize_keyboard'   => true,
			'one_time_keyboard' => false,
		]);


		/**
		 * Display Typing...
		 */
		$this->replyWithChatAction(['action' => Actions::TYPING]);


		/**
		 * Verify user
		 */
		$user = new Users($id);
		if ($user->exist() == false) {

			$this->triggerCommand('start');

		} else {


			mail("ylafontaine@addison-electronique.com", "test", $arguments);

			/**
			 * Response
			 */
			$this->replyWithMessage([
				'text'         => $arguments[0],
				'reply_markup' => $reply_markup,
				'parse_mode'   => 'HTML',
			]);


		}
	}
}