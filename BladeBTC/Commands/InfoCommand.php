<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Models\Users;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class InfoCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "info";

	/**
	 * @var string Command Description
	 */
	protected $description = "Info menu.";

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
			 * Response
			 */
			$this->replyWithMessage([
				'text'         => "<b>First steps</b>🎽

Your perfect start.

✏️ First of all you need your own Crypto-Wallet and some BTC on it (minimum invest is " . getenv("MINIMUM_INVEST") . " BTC). To create your own wallet ask around in the groups or your sponsor, it is really simple. I can recommend platforms like Bittrex, Blockchain or Bitpanda.

✏️ " . getenv("APP_NAME") . " offers you and your partners " . getenv("BASE_RATE") . "% on a daily base for " . getenv("CONTRACT_DAY") . " days. You will receive " . (getenv("BASE_RATE") / (24 / getenv("TIMER_TIME_HOUR"))) . "% every " . getenv("TIMER_TIME_HOUR") . " hours from the moment you deposit, until the " . getenv("CONTRACT_DAY") . " days are over. The minimum invest is " . getenv("MINIMUM_INVEST") . " BTC and the minimum for a withdraw is " . getenv("MINIMUM_PAYOUT") . " BTC. You can invest as many times as you want and I also offer you to reinvest your balance, the minimum for reinvest is " . getenv("MINIMUM_REINVEST") . " BTC.

<b>Deposit - Invest</b> 💵

✏️ Push the invest button to do your first investment

✏️ Now you see a long Wallet-ID with numbers and letters. Copy this address and send the amount you want to invest from your own wallet to the wallet displayed inside the " . getenv("APP_NAME") . ".

✏️ You can check that always on the \"My balance\" button. You also will find there all information about how many days are left in your current investments. Sometimes the investments or withdrawals can take a little longer, but that is not on me, as it is checked by Blockchain.

✏️ And from now on you earn " . getenv("BASE_RATE") . "% daily, " . (getenv("BASE_RATE") / (24 / getenv("TIMER_TIME_HOUR"))) . "% every " . getenv("TIMER_TIME_HOUR") . " hours until the " . getenv("CONTRACT_DAY") . " days are over.

✏️ It is on you, if you want to invest your current balance again or if you want to withdraw it. If you want to reinvest your current balance you simple press the button “Reinvest”. Beside your other investments your now invested again with a new " . getenv("CONTRACT_DAY") . " day plan. Minimum for reinvest is " . getenv("MINIMUM_REINVEST") . " BTC.

<b>Withdraw</b> 💼

✏ Press \"Withdraw\" button to withdraw your money to your BTC wallet.

✏️ Feels free to payout your available account balance at any time once all 24 hours. The minimum to withdraw is " . getenv("MINIMUM_PAYOUT") . " BTC.

✏️ Before pushing \"Withdraw\" paste your personal Wallet-ID into the chat window and press enter to setup your Wallet-ID.

✏️ Your wallet is now registered and connected with your Telegram account. Now choose the amount you want to payout as follows. Type into your chat the command: /out 0.08 (the 0.08 are just an example). As soon as you press enter now and your balance is high enough for the payout, you will get the immediate confirmation. Your money is now on the way to your personal BTC wallet within normally two hours.

✏️ To change your current payout wallet, you simply past your new wallet into the chat, that's it.

<b>Support</b> \xF0\x9F\x92\xAC

✏ " . getenv("SUPPORT_CHAT_ID"),
				'reply_markup' => $reply_markup,
				'parse_mode'   => 'HTML',
			]);

		}
	}
}