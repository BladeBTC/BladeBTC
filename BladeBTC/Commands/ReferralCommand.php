<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Models\Users;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class ReferralCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "referral";

	/**
	 * @var string Command Description
	 */
	protected $description = "Referral menu";

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


			$this->replyWithMessage([
				'text'         => "<b>Referral System:</b>

Level 1 - 10%
Level 2 - 5%
Level 3 - 2%

<b>Your referral link to share with your friends:</b>
" . $user->getReferralLink(),
				'reply_markup' => $reply_markup,
				'parse_mode'   => 'HTML',
			]);


		}
	}
}