<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Models\Users;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class SmsAllCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "sms";

	/**
	 * @var string Command Description
	 */
	protected $description = "Send message to all bot member";

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
			 * Send message to all account
			 */
			$ids = Users::getAllChatId();
			foreach ($ids as $id) {
				try {
					$this->replyWithMessage([
						'chat_id' => $id,
						'text'    => $arguments,
					]);
				} catch (\Exception $e) {
					//Continue to send message to other user if one of this user block this bot.
				}
			}

			/**
			 * Response
			 */
			$this->replyWithMessage([
				'text'         => "SMS Successfully send.",
				'reply_markup' => $reply_markup,
				'parse_mode'   => 'HTML',
			]);
		}
	}
}