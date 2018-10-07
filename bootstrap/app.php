<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', getenv("DEBUG"));

require __DIR__ . '/../vendor/autoload.php';
