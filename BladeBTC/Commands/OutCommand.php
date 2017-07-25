<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Helpers\Database;
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

				if (empty($transaction['error']) && !empty($transaction)) {

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
					 * Update balance and payout
					 */
					$db = Database::get();
					$db->query("   UPDATE
                                              `users`
                                            SET 
                                              `balance` = `balance` - " . $db->quote($out_amount) . ",
                                              `payout` = `payout` + " . $db->quote($out_amount) . "
                                            WHERE
                                                `telegram_id` = " . $user->getTelegramId() . "
                                            ");

					/**
					 * Response
					 */
					$this->replyWithMessage([
						'text'         => "Message :\n<b>" . $transaction['message'] . "</b>\n" . "Transaction ID:\n<b>" . $transaction['tx_hash'] . "</b>\n" . "Notice:\n<b>" . $transaction['notice'] . "</b>",
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
						"message"          => $transaction['error'],
						"tx_hash"          => "",
						"notice"           => "",
						"status"           => 0,
						"type"             => "withdraw",
					]);


					/**
					 * Response
					 */
					$this->replyWithMessage([
						'text'         => "An error occurred while withdrawing your BTC.\n<b>[Error] " . $transaction['error'] . "</b>\nPlease contact support with this account ID : <b>" . $user->getTelegramId() . "</b>. \xF0\x9F\x98\x96",
						'reply_markup' => $reply_markup,
						'parse_mode'   => 'HTML',
					]);
				}
			}
		}
	}
}