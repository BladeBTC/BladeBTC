<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use BladeBTC\Helpers\Wallet;

$w = new Wallet();
echo $w->getDepositAddress("BTC");