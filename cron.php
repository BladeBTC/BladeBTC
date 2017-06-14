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
	$deposit = Wallet::listAddress();
	echo '<pre>';
	print_r($deposit);


} catch (Exception $e) {

	if (getenv("DEBUG") == 1) {
		mail(getenv("MAIL"), "BOT ERROR", $e->getMessage() . "\n" . $e->getFile() . "[" . $e->getLine() . "]");
	}
}


