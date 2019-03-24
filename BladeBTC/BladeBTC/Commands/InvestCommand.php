<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Helpers\Wallet;
use BladeBTC\Models\Users;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class InvestCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "invest";

	/**
	 * @var string Command Description
	 */
	protected $description = "Load invest menu.";

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
			 * Generate payment address
			 */
			$payment_address = Wallet::generateAddress($user->getTelegramId());


			/**
			 * Validate payment address and reply
			 */
			if (!empty($payment_address)) {

				/**
				 * Store investment_address
				 */
				$user->setInvestmentAddress($payment_address);


				/**
				 * Response
				 */
				$this->replyWithMessage([
					'text'         => "Here is your personal BTC address for your investments:",
					'reply_markup' => $reply_markup,
					'parse_mode'   => 'HTML'
				]);

				$this->replyWithMessage([
					'text'         => "<b>$payment_address</b>",
					'reply_markup' => $reply_markup,
					'parse_mode'   => 'HTML'
				]);

				$this->replyWithMessage([
					'text'         => "You may invest at anytime and as much as you want (minimum " . getenv("MINIMUM_INVEST") . " BTC). After correct transfer, your funds will be added to your account during an hour. Have fun and enjoy your daily profit!",
					'reply_markup' => $reply_markup,
					'parse_mode'   => 'HTML'
				]);
			} else {
				$this->replyWithMessage([
					'text'         => "An error occurred while generating your payment address.\nPlease contact support with this account ID : <b>" . $user->getTelegramId() . "</b>. \xF0\x9F\x98\x96",
					'reply_markup' => $reply_markup,
					'parse_mode'   => 'HTML'
				]);
			}
		}
	}
}