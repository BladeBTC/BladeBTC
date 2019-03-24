<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Models\Users;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class ReinvestCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "reinvest";

	/**
	 * @var string Command Description
	 */
	protected $description = "Load reinvest menu.";

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


			if ($user->getBalance() < getenv("MINIMUM_REINVEST")) {

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
				 * Response
				 */
				$this->replyWithMessage([
					'text'         => "Sorry to tell you that, but your balance is not high enough for that!\n<b>Min: " . getenv("MINIMUM_REINVEST") . " BTC</b>",
					'reply_markup' => $reply_markup,
					'parse_mode'   => 'HTML',
				]);

			} else {

				/**
				 * Reinvest balance
				 */
				$user->Reinvest();
				$user->Refresh();

				/**
				 * Keyboard + Refresh balance
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
				 * Response
				 */
				$this->replyWithMessage([
					'text'         => "Congratulation your balance has been properly invested!",
					'reply_markup' => $reply_markup,
					'parse_mode'   => 'HTML',
				]);


				/**
				 * Show new balance
				 */
				$this->triggerCommand("balance");
			}

		}
	}
}