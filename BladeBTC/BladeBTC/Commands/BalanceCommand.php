<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Models\Investment;
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
			 * Contract list
			 */
			$investment = Investment::getActiveInvestment($user->getTelegramId());
			if (count($investment) > 0) {
				$investment_data = "\n|   Amount   |   Rate   |   End   |\n";
				foreach ($investment as $row) {
					$investment_data .= "|" . $row->amount . "|" . $row->rate . "%|" . $row->contract_end_date . "|\n";
				}
			} else {
				$investment_data = "No active investment, start now with just " . getenv("MINIMUM_INVEST") . " BTC";
			}

			/**
			 * Response
			 */
			$this->replyWithMessage([
				'text'         => "Your account balance:
<b>" . Btc::Format($user->getBalance()) . "</b> BTC\n
Total invested:
<b>" . Btc::Format($user->getInvested()) . "</b> BTC\n
Active investment:
<b>" . Btc::Format(Investment::getActiveInvestmentTotal($user->getTelegramId())) . "</b> BTC\n
Total profit:
<b>" . Btc::Format($user->getProfit()) . "</b> BTC\n
Total Payout:
<b>" . Btc::Format($user->getPayout()) . "</b> BTC\n
Total commission:
<b>" . Btc::Format($user->getCommission()) . "</b> BTC\n
<b>Your investment:</b>
" . $investment_data . "
\nBase rate: <b>" . getenv("BASE_RATE") . "% per day for " . getenv("CONTRACT_DAY") . " days</b>\n
You may start another investment by pressing the \"Invest\" button. Your balance will grow according to the base rate.",
				'reply_markup' => $reply_markup,
				'parse_mode'   => 'HTML',
			]);
		}
	}
}