<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use BladeBTC\Helpers\Wallet;
use Telegram\Bot\Api;

try {

	/**
	 * Load .env file
	 */
	$dotenv = new Dotenv\Dotenv(__DIR__);
	$dotenv->load();

	/**
	 * Connect Telegram API
	 */
	$telegram = new Api(getenv('APP_ID'));


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

				//credit account and all stuff
			} else {
				echo 'transaction : ' . $check_address . " not confirmed!";
			}
		}
	}

} catch (Exception $e) {

	if (getenv("DEBUG") == 1) {
		mail(getenv("MAIL"), "BOT ERROR", $e->getMessage() . "\n" . $e->getFile() . "[" . $e->getLine() . "]");
	}
}


