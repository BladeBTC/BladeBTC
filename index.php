<?php

use BladeBTC\WebHookHandler;

session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

$webHook = new WebHookHandler();