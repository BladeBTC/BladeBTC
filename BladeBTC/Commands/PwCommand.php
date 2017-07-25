<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Helpers\Wallet;
use BladeBTC\Models\Users;
use BladeBTC\Models\Passwd;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class PwCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "pw";

	/**
	 * @var string Command Description
	 */
	protected $description = "Set second password";

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

			try {

				/**
				 * Save password
				 */
				Passwd::set(trim($arguments));

				/**
				 * Response
				 */
				$this->replyWithMessage([
					'text'         => "Password successfully saved.",
					'reply_markup' => $reply_markup,
					'parse_mode'   => 'HTML',
				]);
			} catch (\Exception $e) {

				/**
				 * Response
				 */
				$this->replyWithMessage([
					'text'         => "Error: " . $e->getMessage(),
					'reply_markup' => $reply_markup,
					'parse_mode'   => 'HTML',
				]);
			}
		}
	}
}