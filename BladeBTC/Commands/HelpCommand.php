<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Models\Users;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

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
			 * Response
			 */
			$this->replyWithMessage([
				'text'         => "First steps🎽

Your perfect start.

✏️ First of all you need your own Crypto-Wallet and some BTC on it (minimum invest is 0.02 BTC). To create your own wallet ask around in the groups or your sponsor, it is really simple. I can recommend platforms like Bittrex, Blockchain or Bitpanda.

✏️ Global CryptoBot offers you and your partners 4% on a daily base for 40 days. You will receive 1% every 6 hours from the moment you deposit, until the 40 days are over. The minimum invest is 0.02 BTC and the minimum for a withdraw is 0.04 BTC. You can invest as many times as you want and I also offer you to reinvest your balance, the minimum for reinvest is 0.02 BTC.",
				'reply_markup' => $reply_markup,
				'parse_mode'   => 'HTML',
			]);

		}
	}
}