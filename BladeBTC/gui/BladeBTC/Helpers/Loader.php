<?php

namespace BladeBTC\GUI\Helpers;

use Dotenv\Dotenv;

/**
 * Validate PHP Version
 */
if (version_compare(phpversion(), '7.0', '<')) {

	echo "Your server is currently using the PHP version ".phpversion().", this site requires at least PHP 7.0 to run.";
	die;
}


/**
 * Allow back on expired web page
 */
session_cache_limiter('private_no_expire, must-revalidate');


/**
 * Start session
 */
session_start();


/**
 * Loading time
 */
$start_time = microtime(true);


/**
 * Time Zone
 */
date_default_timezone_set('America/Montreal');

/**
 * MSSQL Charset
 */
ini_set('mssql.charset', 'UTF-8');

/**
 * Load composer components
 */
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';


/**
 * Set time zone
 */
date_default_timezone_set('America/Montreal');


/**
 * Load .env file
 */
$dotenv = new Dotenv($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();


/**
 * Debug bar initialization
 * Also early initialize database class
 */
Debugbar::init();


/**
 * Error setting
 */
if (getenv("DEBUG") == 1) {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
} else {
	error_reporting(0);
	ini_set('display_errors', 0);
}





