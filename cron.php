<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', getenv("DEBUG"));

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use BladeBTC\Helpers\Btc;
use BladeBTC\Helpers\Database;
use BladeBTC\Helpers\Wallet;
use BladeBTC\Models\Investment;
use BladeBTC\Models\Transactions;
use BladeBTC\Models\Users;

try {

	/**
	 * Load .env file
	 */
	$dotenv = new Dotenv\Dotenv(__DIR__);
	$dotenv->load();

	/*
	 * ===========================================  HANDLE DEPOSIT ==========================================
	 */

	/**
	 * Recover all address
	 */
	$addresses = Wallet::listAddress();
	foreach ($addresses['addresses'] as $address) {

		/**
		 * Check if address have balance
		 */
		if ($address['total_received'] > 0) {


			/**
			 * Check if transaction have 6 confirmation
			 */
			$check_address = $address['address'];
			if (file_get_contents("https://blockchain.info/q/getreceivedbyaddress/$check_address?confirmations=" . getenv("REQUIRED_CONFIRMATIONS")) != 0) {

				$db = Database::get();

				try {

					$db->beginTransaction();

					/**
					 * Build user object
					 */
					$user = new Users($address['label']);

					/**
					 * Create investment
					 */
					Investment::create($user->getTelegramId(), Btc::SatoshiToBitcoin($address['total_received']), $user->getUserRate());


					/**
					 * Update invested
					 */
					$db->query("   UPDATE
                                              `users`
                                            SET 
                                              `invested` = `invested` + " . $db->quote(Btc::SatoshiToBitcoin($address['total_received'])) . "
                                            WHERE
                                                `telegram_id` = " . $user->getTelegramId() . "
                                            ");

					/**
					 * Give bonus to referent
					 */
					$referent_id = $db->query("   SELECT
                                              `telegram_id_referent`
                                            FROM 
                                              `referrals`
                                            WHERE
                                                `telegram_id_referred` = " . $user->getTelegramId() . "
                                            ")->fetchObject();


					if (is_object($referent_id) && !empty($referent_id->telegram_id_referent)) {

						/**
						 * Calculate commision
						 */
						$rate = getenv("COMMISSION_RATE");
						$commission = Btc::SatoshiToBitcoin($address['total_received']) * $rate / 100;

						$db->query("   UPDATE
                                              `users`
                                            SET 
                                              `commission` = `commission` + " . $db->quote($commission) . ",
                                              `balance` = `balance` + " . $db->quote($commission) . "
                                            WHERE
                                                `telegram_id` = " . $referent_id->telegram_id_referent . "
                                            ");

					}

					/**
					 * Log transaction
					 */
					Transactions::log([
						"telegram_id"      => $user->getTelegramId(),
						"amount"           => Btc::SatoshiToBitcoin($address['total_received']),
						"withdraw_address" => "",
						"message"          => "",
						"tx_hash"          => "",
						"notice"           => "",
						"status"           => 1,
						"type"             => "deposit",
					]);

					$db->commit();

				} catch (\Exception $e) {
					$db->rollBack();
					throw new \Exception($e->getMessage());
				}
			}
		}
	}

} catch (Exception $e) {

	if (getenv("DEBUG") == 1) {
		mail(getenv("MAIL"), "BOT ERROR", $e->getMessage() . "\n" . $e->getFile() . "[" . $e->getLine() . "]");
	}
}


