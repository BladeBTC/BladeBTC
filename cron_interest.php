<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', getenv("DEBUG"));

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use BladeBTC\Models\Investment;

try {

	/**
	 * Load .env file
	 */
	$dotenv = new Dotenv\Dotenv(__DIR__);
	$dotenv->load();

	/*
  	 * ===========================================  GIVE INTEREST ==========================================
	 * Interest must be givin before handle deposit.
     */

	Investment::giveInterest();

} catch (Exception $e) {

	if (getenv("DEBUG") == 1) {
		mail(getenv("MAIL"), "BOT ERROR", $e->getMessage() . "\n" . $e->getFile() . "[" . $e->getLine() . "]");
	}
}


