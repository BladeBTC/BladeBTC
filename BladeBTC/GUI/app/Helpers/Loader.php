<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 26/07/2017
 * Time: 11:03
 */

namespace App\Helpers;

use Dotenv\Dotenv;

/**
 * Validate PHP Version
 */
if (version_compare(phpversion(), '7.0', '<')) {

	echo "Votre serveur utilise actuellement la version " . phpversion() . " de PHP, ce site nécessite au moins la version 7.0 de PHP pour fonctionner.";
	die;
}


/**
 * Permet le retour en arrière sur une page expiré
 */
session_cache_limiter('private_no_expire, must-revalidate');


/**
 * Start session
 */
session_start();


/**
 * Permet de calculer le temps de loading de la page
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





