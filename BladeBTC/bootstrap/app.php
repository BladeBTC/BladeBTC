<?php
/**
 * Start Session
 */

session_start();

/**
 * Auto Load
 */
require __DIR__ . '/../vendor/autoload.php';

/**
 * Load .env file
 */
$dotenv = new Dotenv\Dotenv(__DIR__.'/../');
$dotenv->load();

/**
 * Display / Hide Errors
 */
if(getenv('DEBUG') == 1){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}





