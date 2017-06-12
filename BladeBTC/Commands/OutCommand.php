<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Helpers\Wallet;
use BladeBTC\Models\Transactions;
use BladeBTC\Models\Users;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class OutCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "out";

	/**
	 * @var string Command Description
	 */
	protected $description = "Withdraw";

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

			$out_amount = trim($arguments);

			/**
			 * Validate payout amount requested
			 */
			if (!is_numeric($out_amount) || $out_amount < getenv("MINIMUM_PAYOUT")) {

				$this->replyWithMessage([
					'text'         => "You need to payout at least " . getenv("MINIMUM_PAYOUT") . " BTC",
					'reply_markup' => $reply_markup,
					'parse_mode'   => 'HTML',
				]);
			} /**
			 * Validate account balance
			 */
			elseif ($user->getBalance() < $out_amount) {

				$this->replyWithMessage([
					'text'         => "Not enough balance.",
					'reply_markup' => $reply_markup,
					'parse_mode'   => 'HTML',
				]);
			} /**
			 * Withdraw
			 */
			else {

				$transaction = Wallet::makeOutgoingPayment($user->getWalletAddress(), Btc::BtcToSatoshi($out_amount));

				if (!empty($transaction)) {

					/**
					 * Log
					 */
					Transactions::log([
						"telegram_id"      => $user->getTelegramId(),
						"amount"           => $out_amount,
						"withdraw_address" => $user->getWalletAddress(),
						"message"          => $transaction['message'],
						"tx_hash"          => $transaction['tx_hash'],
						"notice"           => $transaction['notice'],
						"status"           => 1,
						"type"             => "withdraw",
					]);


					/**
					 * Response
					 */
					$this->replyWithMessage([
						'text'         => "Message :\n" . $transaction['message'] . "\n" . "Transaction ID:\n" . $transaction['tx_hash'] . "\n" . "Notice:\n" . $transaction['notice'],
						'reply_markup' => $reply_markup,
						'parse_mode'   => 'HTML',
					]);
				} else {

					/**
					 * Log
					 */
					Transactions::log([
						"telegram_id"      => $user->getTelegramId(),
						"amount"           => $out_amount,
						"withdraw_address" => $user->getWalletAddress(),
						"message"          => $transaction['message'],
						"tx_hash"          => $transaction['tx_hash'],
						"notice"           => $transaction['notice'],
						"status"           => 1,
						"type"             => "withdraw",
					]);


					/**
					 * Response
					 */
					$this->replyWithMessage([
						'text'         => "An error occurred while withdrawing your BTC.\nPlease contact support with this account ID : <b>" . $user->getTelegramId() . "</b>. \xF0\x9F\x98\x96",
						'reply_markup' => $reply_markup,
						'parse_mode'   => 'HTML',
					]);
				}
			}
		}
	}
}