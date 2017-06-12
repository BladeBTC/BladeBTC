<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Models\Users;
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
			["Reinvest \xE2\x86\xA9", "Help \xE2\x9D\x93"],
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

			/**
			 * Response
			 */
			$this->replyWithMessage([
				'text'         => "Your account balance:
<b>" . Btc::Format($user->getBalance()) . "</b> BTC\n
Total invested:
<b>" . Btc::Format($user->getInvested()) . "</b> BTC\n
Active investment:
<b>" . Btc::Format($user->getActiveInvestment()) . "</b>/125 BTC\n
Total profit:
<b>" . Btc::Format($user->getProfit()) . "</b> BTC\n
Total Payout:
<b>" . Btc::Format($user->getPayout()) . "</b> BTC\n
<b>Your investment:</b>
" . ($user->getActiveInvestment() == 0 ? "No active investment, start now with just " . getenv("MINIMUM_INVEST") . " BTC" : Btc::Format($user->getActiveInvestment())) . "
\nBase rate: <b>" . getenv("BASE_RATE") . "% per day.</b>\n
You may start another investment by pressing the \"Invest\" button. Your balance will grow according to the base rate.",
				'reply_markup' => $reply_markup,
				'parse_mode'   => 'HTML',
			]);
		}
	}
}