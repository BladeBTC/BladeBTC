<?php


namespace BladeBTC\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class ErrorCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "error";

	/**
	 * @var string Command Description
	 */
	protected $description = "Go to error";

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
		 * Response
		 */
		$this->replyWithMessage([
			'text'         => "Looks like you failed.",
			'reply_markup' => $reply_markup,
			'parse_mode'   => 'HTML',
		]);

	}
}