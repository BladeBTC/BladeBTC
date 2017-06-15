<?php

namespace BladeBTC\Helpers;

/**
 * Class Wallet
 *
 * @package BladeBTC\Helpers
 * @see     https://blockchain.info/api/blockchain_wallet_api
 */
class Wallet
{
	/**
	 * Generate payment address
	 *
	 * @param $telegram_user_id - ID of the current user requesting address
	 *
	 * @return string - Payment address
	 */
	public static function generateAddress($telegram_user_id)
	{

		/**
		 * Database connexion
		 */
		$db = Database::get();

		/**
		 * Select address from users database if exist
		 */
		$wallet_address = $db->query("SELECT investment_address FROM users WHERE telegram_id = '" . $telegram_user_id . "'")->fetchObject()->investment_address;
		if (!is_null($wallet_address) || !empty($wallet_address)) {
			return $wallet_address;
		} else {

			/**
			 * Param
			 */
			$wallet = getenv("WALLET_ID");
			$main_password = getenv("WALLET_PASSWORD");
			$label = $telegram_user_id;

			/**
			 * Request URL
			 */
			$json_url = "http://127.0.0.1:3000/merchant/$wallet/new_address?password=$main_password&label=$label";

			/**
			 * Request
			 */
			$json_data = file_get_contents($json_url);
			$json_feed = json_decode($json_data);


			return $json_feed->address;
		}
	}


	/**
	 * Get wallet balance
	 *
	 * @return mixed
	 */
	public static function getBalance()
	{

		/**
		 * Param
		 */
		$wallet = getenv("WALLET_ID");
		$main_password = getenv("WALLET_PASSWORD");

		/**
		 * Request URL
		 */
		$json_url = "http://127.0.0.1:3000/merchant/$wallet/balance?password=$main_password";

		/**
		 * Request
		 */
		$json_data = file_get_contents($json_url);
		$json_feed = json_decode($json_data);


		return $json_feed->balance;
	}


	/**
	 * Get address balance
	 *
	 * @param $address - Address to query
	 *
	 * @return array - ["balance", "address", "total_received"]
	 */
	public static function getAddressBalance($address)
	{
		/**
		 * Param
		 */
		$wallet = getenv("WALLET_ID");
		$main_password = getenv("WALLET_PASSWORD");

		/**
		 * Request URL
		 */
		$json_url = "http://127.0.0.1:3000/merchant/$wallet/address_balance?password=$main_password&address=$address";

		/**
		 * Request
		 */
		$json_data = file_get_contents($json_url);
		$json_feed = json_decode($json_data, true);


		return $json_feed;
	}


	/**
	 * Send bitcoin to a specific address
	 *
	 * @param $to_wallet_address - Wallet address
	 * @param $satoshi_amount    - Satoshi amount
	 *
	 * @return array - ["message" => "Response Message", "tx_hash" => "Transaction Hash", "notice" => "Additional
	 *               Message"]
	 */
	public static function makeOutgoingPayment($to_wallet_address, $satoshi_amount)
	{
		/**
		 * Param
		 */
		$wallet = getenv("WALLET_ID");
		$main_password = getenv("WALLET_PASSWORD");

		/**
		 * Request URL
		 */
		$json_url = "http://127.0.0.1:3000/merchant/$wallet/payment?password=$main_password&to=$to_wallet_address&amount=$satoshi_amount";

		/**
		 * Request
		 */
		$json_data = file_get_contents($json_url);
		$json_feed = json_decode($json_data);


		return $json_feed;
	}


	/**
	 * List address
	 *
	 * @return mixed
	 * @see    https://blockchain.info/q/getblockcount
	 */
	public static function listAddress()
	{
		/**
		 * Param
		 */
		$wallet = getenv("WALLET_ID");
		$main_password = getenv("WALLET_PASSWORD");

		/**
		 * Request URL
		 */
		$json_url = "http://127.0.0.1:3000/merchant/$wallet/list?password=$main_password";

		/**
		 * Request
		 */
		$json_data = file_get_contents($json_url);
		$json_feed = json_decode($json_data, true);


		return $json_feed;
	}

	/**
	 * List address
	 *
	 * @return mixed
	 * @see    https://blockchain.info/q/getblockcount
	 */
	public static function createHD()
	{
		/**
		 * Param
		 */
		$wallet = getenv("WALLET_ID");
		$main_password = getenv("WALLET_PASSWORD");

		/**
		 * Request URL
		 */
		$json_url = "http://127.0.0.1:3000/merchant/$wallet/accounts/xpub6DAb91bswKrRwCgghnP9cyu37KMGVEQmo9ZJG7o7dayxn3QUrR2oZ5GZ36UpWHifGi6mE2WozsLde4U3atetc4WcNgs48axMzVknQBwjkEN?password=$main_password";

		/**
		 * Request
		 */
		$json_data = file_get_contents($json_url);
		$json_feed = json_decode($json_data, true);


		return $json_feed;
	}

}