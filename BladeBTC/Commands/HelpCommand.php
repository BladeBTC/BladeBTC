<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Models\Users;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

class HelpCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "help";

	/**
	 * @var string Command Description
	 */
	protected $description = "Help menu.";

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

			/**
			 * Keyboard
			 */
			$keyboard = [
				["My balance " . Btc::Format($user->getBalance()) . " \xF0\x9F\x92\xB0"],
				["Invest \xF0\x9F\x92\xB5", "Withdraw \xE2\x8C\x9B"],
				["Reinvest \xE2\x86\xA9", "Help \xE2\x9D\x93"],
				["My Team \xF0\x9F\x91\xAB"],
			];

			$reply_markup = $this->telegram->replyKeyboardMarkup([
				'keyboard'          => $keyboard,
				'resize_keyboard'   => true,
				'one_time_keyboard' => false,
			]);

			/**
			 * Keyboard Inline
			 */
			$inlineLayout = [
				[
					Keyboard::inlineButton(['text' => 'Test', 'callback_data' => 'data']),
					Keyboard::inlineButton(['text' => 'Btn 2', 'callback_data' => 'data_from_btn2']),
				],
			];

			$keyboard = $this->telegram->replyKeyboardMarkup([
				'inline_keyboard' => $inlineLayout,
			]);


			/**
			 * Response
			 */
			$this->replyWithMessage([
				'text'         => "Press one of the buttons below to get more information about how to start, I am very happy to work with you.",
				'reply_markup' => $reply_markup,
				'parse_mode'   => 'HTML',
			]);

			$this->replyWithMessage([
				'reply_markup' => $keyboard,
				'parse_mode'   => 'HTML',
			]);
		}
	}
}